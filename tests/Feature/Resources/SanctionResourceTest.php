<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Resources\SanctionResource;
use AustinW\UsaGym\Resources\ReservationResource;
use AustinW\UsaGym\Resources\VerificationResource;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('SanctionResource', function () {
    describe('initialization', function () {
        it('stores the sanction ID', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->sanctions(58025);

            expect($resource)->toBeInstanceOf(SanctionResource::class)
                ->and($resource->getSanctionId())->toBe(58025);
        });

        it('accepts different sanction IDs', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource1 = $connector->sanctions(12345);
            $resource2 = $connector->sanctions(67890);

            expect($resource1->getSanctionId())->toBe(12345)
                ->and($resource2->getSanctionId())->toBe(67890);
        });
    });

    describe('getSanctionId() method', function () {
        it('returns the sanction ID', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->sanctions(58025);

            expect($resource->getSanctionId())->toBe(58025);
        });
    });

    describe('reservations() method', function () {
        it('returns ReservationResource instance', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->sanctions(58025)->reservations();

            expect($resource)->toBeInstanceOf(ReservationResource::class);
        });

        it('passes the sanction ID to ReservationResource', function () {
            $mockClient = new MockClient([
                '*' => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            // Calling athletes() on ReservationResource should use the correct sanction ID
            $connector->sanctions(58025)->reservations()->athletes();

            $mockClient->assertSent(function ($request): bool {
                return str_contains($request->resolveEndpoint(), '/sanction/58025/');
            });
        });
    });

    describe('verification() method', function () {
        it('returns VerificationResource instance', function () {
            $connector = new UsaGym('test-user', 'test-pass');

            $resource = $connector->sanctions(58025)->verification();

            expect($resource)->toBeInstanceOf(VerificationResource::class);
        });

        it('passes the sanction ID to VerificationResource', function () {
            $mockClient = new MockClient([
                '*' => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => [loadFixture('verification.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            // Calling athletes() on VerificationResource should use the correct sanction ID
            $connector->sanctions(58025)->verification()->athletes(['987654']);

            $mockClient->assertSent(function ($request): bool {
                return str_contains($request->resolveEndpoint(), '/sanction/58025/');
            });
        });
    });

    describe('fluent API chain', function () {
        it('supports full fluent chain for reservations', function () {
            $mockClient = new MockClient([
                '*' => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('athlete.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector
                ->sanctions(58025)
                ->reservations()
                ->athletes();

            expect($athletes)->toBeArray()
                ->and($athletes)->toHaveCount(1);
        });

        it('supports full fluent chain for verification', function () {
            $mockClient = new MockClient([
                '*' => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => [loadFixture('verification.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector
                ->sanctions(58025)
                ->verification()
                ->athletes(['987654']);

            expect($results)->toBeArray()
                ->and($results)->toHaveCount(1);
        });

        it('creates independent resource chains for different sanctions', function () {
            $mockClient = new MockClient([
                '*' => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $sanction1 = $connector->sanctions(58025);
            $sanction2 = $connector->sanctions(58026);

            expect($sanction1->getSanctionId())->toBe(58025)
                ->and($sanction2->getSanctionId())->toBe(58026);

            $reservations1 = $sanction1->reservations();
            $reservations2 = $sanction2->reservations();

            expect($reservations1)->not->toBe($reservations2);
        });
    });
});
