<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Requests\Person\PersonExistsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('PersonResource', function () {
    describe('exists() method', function () {
        it('returns true when person exists with valid credentials', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'valid' => true,
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->person()->exists('987654', 'Smith', '2010-05-15');

            expect($result)->toBeTrue();
        });

        it('returns false when person does not exist', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'valid' => false,
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->person()->exists('000000', 'Unknown', '2000-01-01');

            expect($result)->toBeFalse();
        });

        it('returns false when credentials do not match', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'valid' => false,
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            // Correct member ID but wrong last name or DOB
            $result = $connector->person()->exists('987654', 'WrongName', '2010-05-15');

            expect($result)->toBeFalse();
        });

        it('accepts DateTimeInterface for date of birth', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'valid' => true,
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $dateOfBirth = new DateTimeImmutable('2010-05-15');
            $result = $connector->person()->exists('987654', 'Smith', $dateOfBirth);

            expect($result)->toBeTrue();
        });

        it('accepts DateTime object for date of birth', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'valid' => true,
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $dateOfBirth = new DateTime('2010-05-15');
            $result = $connector->person()->exists('987654', 'Smith', $dateOfBirth);

            expect($result)->toBeTrue();
        });

        it('handles missing valid key in response as false', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->person()->exists('987654', 'Smith', '2010-05-15');

            expect($result)->toBeFalse();
        });
    });

    describe('request structure', function () {
        it('sends correct query parameters', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->person()->exists('987654', 'Smith', '2010-05-15');

            $mockClient->assertSent(function (PersonExistsRequest $request): bool {
                $query = $request->query()->all();

                return $query['m'] === '987654'
                    && $query['lname'] === 'Smith'
                    && $query['dob'] === '2010-05-15';
            });
        });

        it('formats DateTimeInterface to Y-m-d format in query', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $dateOfBirth = new DateTimeImmutable('2010-05-15');
            $connector->person()->exists('987654', 'Smith', $dateOfBirth);

            $mockClient->assertSent(function (PersonExistsRequest $request): bool {
                $query = $request->query()->all();

                return $query['dob'] === '2010-05-15';
            });
        });

        it('makes GET request to /person/exists endpoint', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->person()->exists('987654', 'Smith', '2010-05-15');

            $mockClient->assertSent(PersonExistsRequest::class);
        });
    });

    describe('fluent API', function () {
        it('supports fluent access from connector', function () {
            $mockClient = new MockClient([
                PersonExistsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector
                ->person()
                ->exists('987654', 'Smith', '2010-05-15');

            expect($result)->toBeTrue();
        });
    });
});
