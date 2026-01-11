<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Data\DisciplineData;
use AustinW\UsaGym\Requests\GetDisciplinesRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('DisciplineResource', function () {
    describe('all() method', function () {
        it('fetches all disciplines from the API', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'disciplines' => loadFixture('disciplines.json'),
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $disciplines = $connector->disciplines()->all();

            expect($disciplines)->toBeArray()
                ->and($disciplines)->toHaveCount(6);
        });

        it('returns an array of DisciplineData objects', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'disciplines' => loadFixture('disciplines.json'),
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $disciplines = $connector->disciplines()->all();

            expect($disciplines[0])->toBeInstanceOf(DisciplineData::class)
                ->and($disciplines[1])->toBeInstanceOf(DisciplineData::class);
        });

        it('correctly maps discipline properties', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'disciplines' => loadFixture('disciplines.json'),
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $disciplines = $connector->disciplines()->all();

            // Test first discipline (WAG)
            expect($disciplines[0]->code)->toBe('WAG')
                ->and($disciplines[0]->name)->toBe('Women')
                ->and($disciplines[0]->fullName)->toBe("Women's Artistic");

            // Test second discipline (MAG)
            expect($disciplines[1]->code)->toBe('MAG')
                ->and($disciplines[1]->name)->toBe('Men')
                ->and($disciplines[1]->fullName)->toBe("Men's Artistic");
        });

        it('returns an empty array when no disciplines are found', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'disciplines' => [],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $disciplines = $connector->disciplines()->all();

            expect($disciplines)->toBeArray()
                ->and($disciplines)->toBeEmpty();
        });

        it('handles missing disciplines key in response', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $disciplines = $connector->disciplines()->all();

            expect($disciplines)->toBeArray()
                ->and($disciplines)->toBeEmpty();
        });

        it('can convert discipline data to enum', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'disciplines' => [
                            ['Code' => 'WAG', 'Name' => 'Women', 'FullName' => "Women's Artistic"],
                        ],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $disciplines = $connector->disciplines()->all();
            $enum = $disciplines[0]->toEnum();

            expect($enum)->toBeInstanceOf(\AustinW\UsaGym\Enums\Discipline::class)
                ->and($enum->value)->toBe('WAG');
        });
    });

    describe('request structure', function () {
        it('makes GET request to /discipline endpoint', function () {
            $mockClient = new MockClient([
                GetDisciplinesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['disciplines' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->disciplines()->all();

            $mockClient->assertSent(GetDisciplinesRequest::class);
        });
    });
});
