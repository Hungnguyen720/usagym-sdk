<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Verification\PersonVerificationRequest;
use AustinW\UsaGym\Data\VerificationResult;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('PersonVerificationRequest', function () {
    it('uses GET method', function () {
        $request = new PersonVerificationRequest(58025, 'athlete', ['12345']);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID and athlete member type', function () {
        $request = new PersonVerificationRequest(58025, 'athlete', ['12345']);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/verification/athlete');
    });

    it('has correct endpoint with coach member type', function () {
        $request = new PersonVerificationRequest(58025, 'coach', ['12345']);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/verification/coach');
    });

    it('has correct endpoint with judge member type', function () {
        $request = new PersonVerificationRequest(58025, 'judge', ['12345']);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/verification/judge');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new PersonVerificationRequest(99999, 'athlete', ['12345']);

        expect($request->resolveEndpoint())->toBe('/sanction/99999/verification/athlete');
    });
});

describe('PersonVerificationRequest query parameters', function () {
    it('builds query with single member ID', function () {
        $request = new PersonVerificationRequest(58025, 'athlete', ['12345']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('people')
            ->and($query['people'])->toBe('12345');
    });

    it('builds query with multiple member IDs as comma-separated string', function () {
        $request = new PersonVerificationRequest(58025, 'athlete', ['12345', '67890', '11111']);

        $query = $request->query()->all();

        expect($query['people'])->toBe('12345,67890,11111');
    });

    it('handles integer member IDs', function () {
        $request = new PersonVerificationRequest(58025, 'athlete', [12345, 67890]);

        $query = $request->query()->all();

        expect($query['people'])->toBe('12345,67890');
    });

    it('handles mixed string and integer member IDs', function () {
        $request = new PersonVerificationRequest(58025, 'coach', ['ABC123', 12345, 'XYZ789']);

        $query = $request->query()->all();

        expect($query['people'])->toBe('ABC123,12345,XYZ789');
    });
});

describe('PersonVerificationRequest::createDtoFromResponse', function () {
    it('creates array of VerificationResult from response', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [
                    'members' => [
                        [
                            'MemberID' => '12345',
                            'LastName' => 'Smith',
                            'FirstName' => 'Jane',
                            'DOB' => '01/15/2010',
                            'USCitizen' => 'Yes',
                            'ClubID' => ['1001'],
                            'ClubAbbrev' => ['ABC'],
                            'ClubName' => ['ABC Gymnastics'],
                            'ClubStatus' => ['Active'],
                            'InternationalClub' => ['No'],
                            'MemberType' => 'ATHL',
                            'Discipline' => ['WAG'],
                            'Level' => 'Level 5',
                            'InternationalMember' => 'No',
                            'Eligible' => true,
                            'IneligibleReason' => null,
                            'Certification' => [
                                'valid' => true,
                                'levels' => ['L5', 'L6'],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'athlete', ['12345']))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(VerificationResult::class)
            ->and($result[0]->memberId)->toBe('12345')
            ->and($result[0]->lastName)->toBe('Smith')
            ->and($result[0]->firstName)->toBe('Jane')
            ->and($result[0]->eligible)->toBeTrue()
            ->and($result[0]->level)->toBe('Level 5');
    });

    it('creates multiple VerificationResult objects', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [
                    'members' => [
                        [
                            'MemberID' => '12345',
                            'LastName' => 'Smith',
                            'FirstName' => 'Jane',
                            'MemberType' => 'ATHL',
                            'Eligible' => true,
                        ],
                        [
                            'MemberID' => '67890',
                            'LastName' => 'Johnson',
                            'FirstName' => 'Bob',
                            'MemberType' => 'ATHL',
                            'Eligible' => false,
                            'IneligibleReason' => 'Membership expired',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'athlete', ['12345', '67890']))->dto();

        expect($result)->toHaveCount(2)
            ->and($result[0]->memberId)->toBe('12345')
            ->and($result[0]->eligible)->toBeTrue()
            ->and($result[1]->memberId)->toBe('67890')
            ->and($result[1]->eligible)->toBeFalse()
            ->and($result[1]->ineligibleReason)->toBe('Membership expired');
    });

    it('handles verification result with certification data', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [
                    'members' => [
                        [
                            'MemberID' => '12345',
                            'LastName' => 'Williams',
                            'FirstName' => 'Sarah',
                            'MemberType' => 'JUDGE',
                            'Eligible' => true,
                            'Certification' => [
                                'valid' => true,
                                'levels' => ['NAT', 'REG', 'STATE'],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'judge', ['12345']))->dto();

        expect($result[0]->certificationValid)->toBeTrue()
            ->and($result[0]->certificationLevels)->toBe(['NAT', 'REG', 'STATE']);
    });

    it('handles verification result without certification data', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [
                    'members' => [
                        [
                            'MemberID' => '12345',
                            'LastName' => 'Smith',
                            'FirstName' => 'Jane',
                            'MemberType' => 'ATHL',
                            'Eligible' => true,
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'athlete', ['12345']))->dto();

        expect($result[0]->certificationValid)->toBeNull()
            ->and($result[0]->certificationLevels)->toBeNull();
    });

    it('handles ineligible member with reason', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [
                    'members' => [
                        [
                            'MemberID' => '12345',
                            'LastName' => 'Banned',
                            'FirstName' => 'Person',
                            'MemberType' => 'ATHL',
                            'Eligible' => false,
                            'IneligibleReason' => 'SafeSport violation',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'athlete', ['12345']))->dto();

        expect($result[0]->eligible)->toBeFalse()
            ->and($result[0]->ineligibleReason)->toBe('SafeSport violation');
    });

    it('returns empty array when no members in response', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [
                    'members' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'athlete', ['12345']))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when members key is missing', function () {
        $mockClient = new MockClient([
            PersonVerificationRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonVerificationRequest(58025, 'athlete', ['12345']))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
