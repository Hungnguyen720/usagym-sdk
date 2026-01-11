<?php

declare(strict_types=1);

use AustinW\UsaGym\Exceptions\ApiException;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('ApiException', function () {
    describe('instantiation', function () {
        it('can be instantiated with only a message', function () {
            $exception = new ApiException('API error occurred');

            expect($exception->getMessage())->toBe('API error occurred');
            expect($exception->getCode())->toBe(0);
            expect($exception->getResponse())->toBeNull();
            expect($exception->getData())->toBeNull();
        });

        it('can be instantiated with all parameters', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Server error'], 500),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $data = ['message' => 'Internal server error', 'trace_id' => 'abc123'];
            $previous = new Exception('Previous exception');

            $exception = new ApiException(
                message: 'API error occurred',
                response: $response,
                data: $data,
                code: 500,
                previous: $previous
            );

            expect($exception->getMessage())->toBe('API error occurred');
            expect($exception->getCode())->toBe(500);
            expect($exception->getResponse())->toBe($response);
            expect($exception->getData())->toBe($data);
            expect($exception->getPrevious())->toBe($previous);
        });

        it('extends UsaGymException', function () {
            $exception = new ApiException('API error');

            expect($exception)->toBeInstanceOf(UsaGymException::class);
        });

        it('extends PHP Exception class', function () {
            $exception = new ApiException('API error');

            expect($exception)->toBeInstanceOf(Exception::class);
        });
    });

    describe('getResponse()', function () {
        it('returns null when no response is provided', function () {
            $exception = new ApiException('API error occurred');

            expect($exception->getResponse())->toBeNull();
        });

        it('returns the Saloon response when provided', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Error'], 500),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new ApiException('API error occurred', $response);

            expect($exception->getResponse())->toBe($response);
            expect($exception->getResponse()->status())->toBe(500);
        });
    });

    describe('getData()', function () {
        it('returns null when no data is provided', function () {
            $exception = new ApiException('API error occurred');

            expect($exception->getData())->toBeNull();
        });

        it('returns the data array when provided', function () {
            $data = [
                'message' => 'Database connection failed',
                'error_code' => 'DB_CONN_ERR',
                'retry_after' => 30,
            ];
            $exception = new ApiException('API error occurred', null, $data);

            expect($exception->getData())->toBe($data);
            expect($exception->getData()['error_code'])->toBe('DB_CONN_ERR');
        });
    });

    describe('getMessage()', function () {
        it('returns the exception message', function () {
            $exception = new ApiException('Something went wrong on the server');

            expect($exception->getMessage())->toBe('Something went wrong on the server');
        });

        it('handles long error messages', function () {
            $longMessage = str_repeat('Error occurred. ', 100);
            $exception = new ApiException($longMessage);

            expect($exception->getMessage())->toBe($longMessage);
        });
    });

    describe('getCode()', function () {
        it('returns 0 by default', function () {
            $exception = new ApiException('API error');

            expect($exception->getCode())->toBe(0);
        });

        it('returns 500 when set', function () {
            $exception = new ApiException('API error', null, null, 500);

            expect($exception->getCode())->toBe(500);
        });

        it('returns 502 when set', function () {
            $exception = new ApiException('Bad Gateway', null, null, 502);

            expect($exception->getCode())->toBe(502);
        });

        it('returns 503 when set', function () {
            $exception = new ApiException('Service Unavailable', null, null, 503);

            expect($exception->getCode())->toBe(503);
        });

        it('returns 400 when set', function () {
            $exception = new ApiException('Bad Request', null, null, 400);

            expect($exception->getCode())->toBe(400);
        });
    });

    describe('common API error scenarios', function () {
        beforeEach(function () {
            $this->connector = new UsaGym('test-user', 'test-pass');
        });

        it('handles internal server error', function () {
            $data = [
                'message' => 'An unexpected error occurred',
                'error' => 'internal_error',
                'trace_id' => 'xyz789',
            ];
            $exception = new ApiException(
                'An unexpected error occurred',
                null,
                $data,
                500
            );

            expect($exception->getMessage())->toBe('An unexpected error occurred');
            expect($exception->getCode())->toBe(500);
            expect($exception->getData()['trace_id'])->toBe('xyz789');
        });

        it('handles bad gateway error', function () {
            $data = [
                'message' => 'The upstream server returned an invalid response',
                'error' => 'bad_gateway',
            ];
            $exception = new ApiException(
                'The upstream server returned an invalid response',
                null,
                $data,
                502
            );

            expect($exception->getMessage())->toBe('The upstream server returned an invalid response');
            expect($exception->getCode())->toBe(502);
        });

        it('handles service unavailable error', function () {
            $data = [
                'message' => 'Service is temporarily unavailable',
                'error' => 'service_unavailable',
                'maintenance_until' => '2024-01-01T12:00:00Z',
            ];
            $exception = new ApiException(
                'Service is temporarily unavailable',
                null,
                $data,
                503
            );

            expect($exception->getMessage())->toBe('Service is temporarily unavailable');
            expect($exception->getCode())->toBe(503);
            expect($exception->getData()['maintenance_until'])->toBe('2024-01-01T12:00:00Z');
        });

        it('handles gateway timeout error', function () {
            $data = [
                'message' => 'Request timed out',
                'error' => 'gateway_timeout',
            ];
            $exception = new ApiException(
                'Request timed out',
                null,
                $data,
                504
            );

            expect($exception->getMessage())->toBe('Request timed out');
            expect($exception->getCode())->toBe(504);
        });

        it('handles bad request error', function () {
            $data = [
                'message' => 'Invalid request format',
                'error' => 'bad_request',
                'details' => 'Missing required field: id',
            ];
            $exception = new ApiException(
                'Invalid request format',
                null,
                $data,
                400
            );

            expect($exception->getMessage())->toBe('Invalid request format');
            expect($exception->getCode())->toBe(400);
            expect($exception->getData()['details'])->toBe('Missing required field: id');
        });
    });
});
