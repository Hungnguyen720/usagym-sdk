<?php

declare(strict_types=1);

use AustinW\UsaGym\Exceptions\AuthenticationException;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('AuthenticationException', function () {
    describe('instantiation', function () {
        it('can be instantiated with only a message', function () {
            $exception = new AuthenticationException('Authentication failed');

            expect($exception->getMessage())->toBe('Authentication failed');
            expect($exception->getCode())->toBe(0);
            expect($exception->getResponse())->toBeNull();
            expect($exception->getData())->toBeNull();
        });

        it('can be instantiated with all parameters', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Unauthorized'], 401),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $data = ['message' => 'Invalid credentials', 'code' => 'AUTH_FAILED'];
            $previous = new Exception('Previous exception');

            $exception = new AuthenticationException(
                message: 'Authentication failed',
                response: $response,
                data: $data,
                code: 401,
                previous: $previous
            );

            expect($exception->getMessage())->toBe('Authentication failed');
            expect($exception->getCode())->toBe(401);
            expect($exception->getResponse())->toBe($response);
            expect($exception->getData())->toBe($data);
            expect($exception->getPrevious())->toBe($previous);
        });

        it('extends UsaGymException', function () {
            $exception = new AuthenticationException('Auth error');

            expect($exception)->toBeInstanceOf(UsaGymException::class);
        });

        it('extends PHP Exception class', function () {
            $exception = new AuthenticationException('Auth error');

            expect($exception)->toBeInstanceOf(Exception::class);
        });
    });

    describe('getResponse()', function () {
        it('returns null when no response is provided', function () {
            $exception = new AuthenticationException('Authentication failed');

            expect($exception->getResponse())->toBeNull();
        });

        it('returns the Saloon response when provided', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Unauthorized'], 401),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new AuthenticationException('Authentication failed', $response);

            expect($exception->getResponse())->toBe($response);
            expect($exception->getResponse()->status())->toBe(401);
        });
    });

    describe('getData()', function () {
        it('returns null when no data is provided', function () {
            $exception = new AuthenticationException('Authentication failed');

            expect($exception->getData())->toBeNull();
        });

        it('returns the data array when provided', function () {
            $data = [
                'message' => 'Token expired',
                'expires_at' => '2024-01-01T00:00:00Z',
            ];
            $exception = new AuthenticationException('Authentication failed', null, $data);

            expect($exception->getData())->toBe($data);
            expect($exception->getData()['message'])->toBe('Token expired');
        });
    });

    describe('getMessage()', function () {
        it('returns the exception message', function () {
            $exception = new AuthenticationException('Invalid API credentials');

            expect($exception->getMessage())->toBe('Invalid API credentials');
        });

        it('returns empty string when empty message is provided', function () {
            $exception = new AuthenticationException('');

            expect($exception->getMessage())->toBe('');
        });
    });

    describe('getCode()', function () {
        it('returns 0 by default', function () {
            $exception = new AuthenticationException('Auth error');

            expect($exception->getCode())->toBe(0);
        });

        it('returns 401 when set', function () {
            $exception = new AuthenticationException('Auth error', null, null, 401);

            expect($exception->getCode())->toBe(401);
        });

        it('returns 403 when set', function () {
            $exception = new AuthenticationException('Forbidden', null, null, 403);

            expect($exception->getCode())->toBe(403);
        });
    });

    describe('common authentication error scenarios', function () {
        beforeEach(function () {
            $this->connector = new UsaGym('test-user', 'test-pass');
        });

        it('handles invalid credentials error', function () {
            $data = [
                'message' => 'The provided credentials are invalid.',
                'error' => 'invalid_credentials',
            ];
            $exception = new AuthenticationException(
                'The provided credentials are invalid.',
                null,
                $data,
                401
            );

            expect($exception->getMessage())->toBe('The provided credentials are invalid.');
            expect($exception->getCode())->toBe(401);
            expect($exception->getData()['error'])->toBe('invalid_credentials');
        });

        it('handles expired token error', function () {
            $data = [
                'message' => 'Token has expired',
                'error' => 'token_expired',
                'expired_at' => '2024-01-01T12:00:00Z',
            ];
            $exception = new AuthenticationException(
                'Token has expired',
                null,
                $data,
                401
            );

            expect($exception->getMessage())->toBe('Token has expired');
            expect($exception->getData()['error'])->toBe('token_expired');
        });

        it('handles forbidden access error', function () {
            $data = [
                'message' => 'Access denied to this resource',
                'error' => 'access_denied',
                'required_permission' => 'admin:read',
            ];
            $exception = new AuthenticationException(
                'Access denied to this resource',
                null,
                $data,
                403
            );

            expect($exception->getMessage())->toBe('Access denied to this resource');
            expect($exception->getCode())->toBe(403);
            expect($exception->getData()['required_permission'])->toBe('admin:read');
        });
    });
});
