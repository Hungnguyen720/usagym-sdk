<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Data\VerificationResult;
use AustinW\UsaGym\Requests\Verification\PersonVerificationRequest;
use AustinW\UsaGym\Requests\Verification\CoachEmailRequest;
use AustinW\UsaGym\Requests\Verification\LegalContactEmailRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('VerificationResource', function () {
    describe('athletes() method', function () {
        it('verifies multiple athletes for a sanction', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [
                            loadFixture('verification.json'),
                            array_merge(loadFixture('verification.json'), ['MemberID' => '987655']),
                        ],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654', '987655']);

            expect($results)->toBeArray()
                ->and($results)->toHaveCount(2);
        });

        it('returns an array of VerificationResult objects', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);

            expect($results[0])->toBeInstanceOf(VerificationResult::class);
        });

        it('correctly maps verification result properties', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);
            $result = $results[0];

            expect($result->memberId)->toBe('987654')
                ->and($result->firstName)->toBe('Jane')
                ->and($result->lastName)->toBe('Smith')
                ->and($result->eligible)->toBeTrue()
                ->and($result->level)->toBe('WLEVEL04')
                ->and($result->clubNames)->toContain('ABC Gymnastics');
        });

        it('sends correct member type in request', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->athletes(['987654']);

            $mockClient->assertSent(function (PersonVerificationRequest $request): bool {
                return str_contains($request->resolveEndpoint(), '/verification/athlete');
            });
        });

        it('sends member IDs in query parameter', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->athletes(['987654', '987655']);

            $mockClient->assertSent(function (PersonVerificationRequest $request): bool {
                $query = $request->query()->all();

                return $query['people'] === '987654,987655';
            });
        });
    });

    describe('athlete() method (singular)', function () {
        it('verifies a single athlete', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->athlete('987654');

            expect($result)->toBeInstanceOf(VerificationResult::class)
                ->and($result->memberId)->toBe('987654');
        });

        it('returns null when athlete not found', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->athlete('000000');

            expect($result)->toBeNull();
        });

        it('accepts integer member ID', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->athlete(987654);

            expect($result)->toBeInstanceOf(VerificationResult::class);
        });
    });

    describe('coaches() method', function () {
        it('verifies multiple coaches for a sanction', function () {
            $coachVerification = array_merge(loadFixture('verification.json'), [
                'MemberType' => 'CCOACH',
            ]);

            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [$coachVerification],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->coaches(['555555']);

            expect($results)->toBeArray()
                ->and($results)->toHaveCount(1)
                ->and($results[0])->toBeInstanceOf(VerificationResult::class);
        });

        it('sends correct member type in request', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->coaches(['555555']);

            $mockClient->assertSent(function (PersonVerificationRequest $request): bool {
                return str_contains($request->resolveEndpoint(), '/verification/coach');
            });
        });
    });

    describe('coach() method (singular)', function () {
        it('verifies a single coach', function () {
            $coachVerification = array_merge(loadFixture('verification.json'), [
                'MemberID' => '555555',
                'MemberType' => 'CCOACH',
            ]);

            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [$coachVerification],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->coach('555555');

            expect($result)->toBeInstanceOf(VerificationResult::class)
                ->and($result->memberId)->toBe('555555');
        });

        it('returns null when coach not found', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->coach('000000');

            expect($result)->toBeNull();
        });
    });

    describe('judges() method', function () {
        it('verifies multiple judges for a sanction', function () {
            $judgeVerification = array_merge(loadFixture('verification.json'), [
                'MemberID' => '666666',
                'MemberType' => 'JUDGE',
            ]);

            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [$judgeVerification],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->judges(['666666']);

            expect($results)->toBeArray()
                ->and($results)->toHaveCount(1);
        });

        it('sends correct member type in request', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->judges(['666666']);

            $mockClient->assertSent(function (PersonVerificationRequest $request): bool {
                return str_contains($request->resolveEndpoint(), '/verification/judge');
            });
        });
    });

    describe('judge() method (singular)', function () {
        it('verifies a single judge', function () {
            $judgeVerification = array_merge(loadFixture('verification.json'), [
                'MemberID' => '666666',
                'MemberType' => 'JUDGE',
            ]);

            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [$judgeVerification],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->judge('666666');

            expect($result)->toBeInstanceOf(VerificationResult::class);
        });

        it('returns null when judge not found', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->judge('000000');

            expect($result)->toBeNull();
        });
    });

    describe('coachEmail() method', function () {
        it('returns true when email is valid for person', function () {
            $mockClient = new MockClient([
                CoachEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->coachEmail(
                refType: 'person',
                refTypeId: '987654',
                email: 'coach@example.com'
            );

            expect($result)->toBeTrue();
        });

        it('returns false when email is invalid', function () {
            $mockClient = new MockClient([
                CoachEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => false],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->coachEmail(
                refType: 'person',
                refTypeId: '987654',
                email: 'invalid@example.com'
            );

            expect($result)->toBeFalse();
        });

        it('works with group reference type', function () {
            $mockClient = new MockClient([
                CoachEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->coachEmail(
                refType: 'group',
                refTypeId: 'GRP001',
                email: 'coach@example.com'
            );

            $mockClient->assertSent(function (CoachEmailRequest $request): bool {
                return str_contains($request->resolveEndpoint(), '/group/GRP001/');
            });
        });

        it('sends correct endpoint structure', function () {
            $mockClient = new MockClient([
                CoachEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->coachEmail(
                refType: 'person',
                refTypeId: '987654',
                email: 'coach@example.com'
            );

            $mockClient->assertSent(function (CoachEmailRequest $request): bool {
                $endpoint = $request->resolveEndpoint();

                return $endpoint === '/sanction/58025/person/987654/verification/coach/email/coach@example.com';
            });
        });

        it('accepts integer refTypeId', function () {
            $mockClient = new MockClient([
                CoachEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->coachEmail(
                refType: 'person',
                refTypeId: 987654,
                email: 'coach@example.com'
            );

            expect($result)->toBeTrue();
        });
    });

    describe('legalContactEmail() method', function () {
        it('returns true when email is a valid legal contact', function () {
            $mockClient = new MockClient([
                LegalContactEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->legalContactEmail(
                refType: 'person',
                refTypeId: '987654',
                email: 'parent@example.com'
            );

            expect($result)->toBeTrue();
        });

        it('returns false when email is not a legal contact', function () {
            $mockClient = new MockClient([
                LegalContactEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => false],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector->sanctions(58025)->verification()->legalContactEmail(
                refType: 'person',
                refTypeId: '987654',
                email: 'unknown@example.com'
            );

            expect($result)->toBeFalse();
        });

        it('sends correct endpoint structure (not sanction-specific)', function () {
            $mockClient = new MockClient([
                LegalContactEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->legalContactEmail(
                refType: 'person',
                refTypeId: '987654',
                email: 'parent@example.com'
            );

            $mockClient->assertSent(function (LegalContactEmailRequest $request): bool {
                $endpoint = $request->resolveEndpoint();

                // Note: legalContactEmail is NOT sanction-specific
                return $endpoint === '/person/987654/verification/legalContact/email/parent@example.com';
            });
        });

        it('works with group reference type', function () {
            $mockClient = new MockClient([
                LegalContactEmailRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['valid' => true],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->legalContactEmail(
                refType: 'group',
                refTypeId: 'GRP001',
                email: 'parent@example.com'
            );

            $mockClient->assertSent(function (LegalContactEmailRequest $request): bool {
                return str_contains($request->resolveEndpoint(), '/group/GRP001/');
            });
        });
    });

    describe('VerificationResult helper methods', function () {
        it('has fullName method', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);

            expect($results[0]->fullName())->toBe('Jane Smith');
        });

        it('has primaryClubId method', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);

            expect($results[0]->primaryClubId())->toBe('12345');
        });

        it('has primaryClubName method', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);

            expect($results[0]->primaryClubName())->toBe('ABC Gymnastics');
        });

        it('has canParticipate method', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);

            expect($results[0]->canParticipate())->toBeTrue();
        });

        it('returns ineligible reason when not eligible', function () {
            $ineligibleData = array_merge(loadFixture('verification.json'), [
                'Eligible' => false,
                'IneligibleReason' => 'Membership expired',
            ]);

            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [$ineligibleData],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $results = $connector->sanctions(58025)->verification()->athletes(['987654']);

            expect($results[0]->eligible)->toBeFalse()
                ->and($results[0]->ineligibleReason)->toBe('Membership expired')
                ->and($results[0]->canParticipate())->toBeFalse();
        });
    });

    describe('fluent API chain', function () {
        it('supports full fluent chain from connector', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'members' => [loadFixture('verification.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $result = $connector
                ->sanctions(58025)
                ->verification()
                ->athlete('987654');

            expect($result)->toBeInstanceOf(VerificationResult::class);
        });

        it('maintains correct sanction ID throughout the chain', function () {
            $mockClient = new MockClient([
                PersonVerificationRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['members' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->verification()->athletes(['987654']);

            $mockClient->assertSent(function (PersonVerificationRequest $request): bool {
                return str_contains($request->resolveEndpoint(), '/sanction/58025/');
            });
        });
    });
});
