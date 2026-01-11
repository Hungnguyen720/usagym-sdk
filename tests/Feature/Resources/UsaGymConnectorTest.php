<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Exceptions\AuthenticationException;
use AustinW\UsaGym\Resources\DisciplineResource;
use AustinW\UsaGym\Resources\PersonResource;
use AustinW\UsaGym\Resources\SanctionResource;
use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('UsaGym Connector', function () {
    describe('initialization', function () {
        it('creates a connector with username and password', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            expect($connector)->toBeInstanceOf(UsaGym::class);
        });

        it('resolves the correct base URL', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            expect($connector->resolveBaseUrl())->toBe('https://api.usagym.org/v4');
        });

        it('sets default headers for JSON content', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $headers = $connector->headers()->all();

            expect($headers)->toHaveKey('Content-Type')
                ->and($headers['Content-Type'])->toBe('application/json')
                ->and($headers)->toHaveKey('Accept')
                ->and($headers['Accept'])->toBe('application/json');
        });
    });

    describe('test() method', function () {
        it('returns true when API credentials are valid', function () {
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make([
                    'status' => 'success',
                    'message' => 'Credentials are valid',
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->test();

            expect($result)->toBeTrue();
        });

        it('returns false when response status is not success', function () {
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make([
                    'status' => 'error',
                    'message' => 'Invalid response',
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->test();

            expect($result)->toBeFalse();
        });

        it('throws AuthenticationException on 401 response', function () {
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make([
                    'message' => 'Invalid credentials',
                ], 401),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->test();
        })->throws(AuthenticationException::class, 'Invalid credentials');

        it('throws AuthenticationException on 403 response', function () {
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make([
                    'message' => 'Forbidden',
                ], 403),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->test();
        })->throws(AuthenticationException::class, 'Forbidden');

        it('uses default error message when response has no message', function () {
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make([], 403),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->test();
        })->throws(AuthenticationException::class, 'Authorization Error: Forbidden');
    });

    describe('resource access', function () {
        it('returns DisciplineResource from disciplines() method', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->disciplines();

            expect($resource)->toBeInstanceOf(DisciplineResource::class);
        });

        it('returns PersonResource from person() method', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->person();

            expect($resource)->toBeInstanceOf(PersonResource::class);
        });

        it('returns SanctionResource from sanctions() method with sanction ID', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->sanctions(58025);

            expect($resource)->toBeInstanceOf(SanctionResource::class)
                ->and($resource->getSanctionId())->toBe(58025);
        });

        it('creates new SanctionResource instances for different sanction IDs', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource1 = $connector->sanctions(58025);
            $resource2 = $connector->sanctions(58026);

            expect($resource1->getSanctionId())->toBe(58025)
                ->and($resource2->getSanctionId())->toBe(58026);
        });
    });

    describe('timeout configuration', function () {
        it('returns default timeout of 30 seconds', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            expect($connector->getTimeout())->toBe(30);
        });

        it('allows setting a custom timeout', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $result = $connector->setTimeout(60);

            expect($result)->toBeInstanceOf(UsaGym::class)
                ->and($connector->getTimeout())->toBe(60);
        });

        it('supports fluent interface for setTimeout', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $result = $connector->setTimeout(45);

            expect($result)->toBe($connector);
        });
    });
});
