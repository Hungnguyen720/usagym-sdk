<?php

declare(strict_types=1);

use AustinW\UsaGym\Exceptions\RateLimitException;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('RateLimitException', function () {
    describe('instantiation', function () {
        it('can be instantiated with only a message', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception->getMessage())->toBe('Rate limit exceeded');
            expect($exception->getCode())->toBe(429);
            expect($exception->getResponse())->toBeNull();
            expect($exception->getData())->toBeNull();
            expect($exception->getRetryAfter())->toBeNull();
        });

        it('can be instantiated with all parameters', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    ['message' => 'Too many requests'],
                    429,
                    ['Retry-After' => '60']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $data = ['message' => 'Rate limit exceeded', 'limit' => 100, 'remaining' => 0];
            $previous = new Exception('Previous exception');

            $exception = new RateLimitException(
                message: 'Rate limit exceeded',
                response: $response,
                data: $data,
                code: 429,
                previous: $previous
            );

            expect($exception->getMessage())->toBe('Rate limit exceeded');
            expect($exception->getCode())->toBe(429);
            expect($exception->getResponse())->toBe($response);
            expect($exception->getData())->toBe($data);
            expect($exception->getPrevious())->toBe($previous);
            expect($exception->getRetryAfter())->toBe(60);
        });

        it('uses custom code when provided', function () {
            $exception = new RateLimitException('Too many requests', null, null, 503);

            expect($exception->getCode())->toBe(503);
        });

        it('extends UsaGymException', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception)->toBeInstanceOf(UsaGymException::class);
        });

        it('extends PHP Exception class', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception)->toBeInstanceOf(Exception::class);
        });
    });

    describe('getRetryAfter()', function () {
        it('returns null when no response is provided', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception->getRetryAfter())->toBeNull();
        });

        it('returns null when response has no Retry-After header', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Too many requests'], 429),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            expect($exception->getRetryAfter())->toBeNull();
        });

        it('returns integer value from Retry-After header', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    ['message' => 'Too many requests'],
                    429,
                    ['Retry-After' => '120']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            expect($exception->getRetryAfter())->toBe(120);
        });

        it('converts string Retry-After header to integer', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    ['message' => 'Too many requests'],
                    429,
                    ['Retry-After' => '30']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            expect($exception->getRetryAfter())->toBe(30);
            expect($exception->getRetryAfter())->toBeInt();
        });

        it('handles small Retry-After values', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    ['message' => 'Too many requests'],
                    429,
                    ['Retry-After' => '1']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            expect($exception->getRetryAfter())->toBe(1);
        });

        it('handles large Retry-After values', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    ['message' => 'Too many requests'],
                    429,
                    ['Retry-After' => '3600']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            expect($exception->getRetryAfter())->toBe(3600);
        });

        it('returns null for zero Retry-After value due to PHP truthiness check', function () {
            // Note: The implementation uses a truthiness check, so '0' is considered falsy
            // and returns null. This documents the actual behavior.
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    ['message' => 'Too many requests'],
                    429,
                    ['Retry-After' => '0']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            // Due to PHP's truthiness check on '0', this returns null
            expect($exception->getRetryAfter())->toBeNull();
        });
    });

    describe('getResponse()', function () {
        it('returns null when no response is provided', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception->getResponse())->toBeNull();
        });

        it('returns the Saloon response when provided', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Too many requests'], 429),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Rate limit exceeded', $response);

            expect($exception->getResponse())->toBe($response);
            expect($exception->getResponse()->status())->toBe(429);
        });
    });

    describe('getData()', function () {
        it('returns null when no data is provided', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception->getData())->toBeNull();
        });

        it('returns the data array when provided', function () {
            $data = [
                'message' => 'Rate limit exceeded',
                'limit' => 100,
                'remaining' => 0,
                'reset_at' => '2024-01-01T12:00:00Z',
            ];
            $exception = new RateLimitException('Rate limit exceeded', null, $data);

            expect($exception->getData())->toBe($data);
            expect($exception->getData()['limit'])->toBe(100);
            expect($exception->getData()['remaining'])->toBe(0);
        });
    });

    describe('getMessage()', function () {
        it('returns the exception message', function () {
            $exception = new RateLimitException('You have exceeded your API rate limit');

            expect($exception->getMessage())->toBe('You have exceeded your API rate limit');
        });
    });

    describe('getCode()', function () {
        it('returns 429 by default', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception->getCode())->toBe(429);
        });

        it('returns custom code when provided', function () {
            $exception = new RateLimitException('Rate limit exceeded', null, null, 503);

            expect($exception->getCode())->toBe(503);
        });
    });

    describe('common rate limit scenarios', function () {
        it('handles hourly rate limit exceeded', function () {
            $data = [
                'message' => 'Hourly rate limit exceeded',
                'limit_type' => 'hourly',
                'limit' => 1000,
                'remaining' => 0,
                'reset_at' => '2024-01-01T13:00:00Z',
            ];

            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    $data,
                    429,
                    ['Retry-After' => '1800']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Hourly rate limit exceeded', $response, $data, 429);

            expect($exception->getMessage())->toBe('Hourly rate limit exceeded');
            expect($exception->getCode())->toBe(429);
            expect($exception->getRetryAfter())->toBe(1800);
            expect($exception->getData()['limit_type'])->toBe('hourly');
        });

        it('handles daily rate limit exceeded', function () {
            $data = [
                'message' => 'Daily rate limit exceeded',
                'limit_type' => 'daily',
                'limit' => 10000,
                'remaining' => 0,
                'reset_at' => '2024-01-02T00:00:00Z',
            ];

            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    $data,
                    429,
                    ['Retry-After' => '43200']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Daily rate limit exceeded', $response, $data, 429);

            expect($exception->getMessage())->toBe('Daily rate limit exceeded');
            expect($exception->getRetryAfter())->toBe(43200);
            expect($exception->getData()['limit_type'])->toBe('daily');
        });

        it('handles concurrent request limit exceeded', function () {
            $data = [
                'message' => 'Too many concurrent requests',
                'limit_type' => 'concurrent',
                'limit' => 10,
                'current' => 10,
            ];

            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(
                    $data,
                    429,
                    ['Retry-After' => '5']
                ),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new RateLimitException('Too many concurrent requests', $response, $data, 429);

            expect($exception->getMessage())->toBe('Too many concurrent requests');
            expect($exception->getRetryAfter())->toBe(5);
            expect($exception->getData()['limit_type'])->toBe('concurrent');
        });
    });
});
