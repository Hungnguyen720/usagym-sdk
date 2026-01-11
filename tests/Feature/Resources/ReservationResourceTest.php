<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Data\AthleteReservation;
use AustinW\UsaGym\Data\CoachReservation;
use AustinW\UsaGym\Data\JudgeReservation;
use AustinW\UsaGym\Data\ClubReservation;
use AustinW\UsaGym\Data\GroupReservation;
use AustinW\UsaGym\Requests\Reservations\GetAthletesRequest;
use AustinW\UsaGym\Requests\Reservations\GetCoachesRequest;
use AustinW\UsaGym\Requests\Reservations\GetJudgesRequest;
use AustinW\UsaGym\Requests\Reservations\GetClubsRequest;
use AustinW\UsaGym\Requests\Reservations\GetGroupsRequest;
use AustinW\UsaGym\Requests\Reservations\GetIndividualsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('ReservationResource', function () {
    describe('athletes() method', function () {
        it('fetches athlete reservations for a sanction', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => loadFixture('athletes.json'),
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector->sanctions(58025)->reservations()->athletes();

            expect($athletes)->toBeArray()
                ->and($athletes)->toHaveCount(2);
        });

        it('returns an array of AthleteReservation objects', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => loadFixture('athletes.json'),
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector->sanctions(58025)->reservations()->athletes();

            expect($athletes[0])->toBeInstanceOf(AthleteReservation::class)
                ->and($athletes[1])->toBeInstanceOf(AthleteReservation::class);
        });

        it('correctly maps athlete properties', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('athlete.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector->sanctions(58025)->reservations()->athletes();
            $athlete = $athletes[0];

            expect($athlete->memberId)->toBe('987654')
                ->and($athlete->firstName)->toBe('Jane')
                ->and($athlete->lastName)->toBe('Smith')
                ->and($athlete->clubName)->toBe('ABC Gymnastics')
                ->and($athlete->level)->toBe('WLEVEL04')
                ->and($athlete->scratched)->toBeFalse();
        });

        it('supports filtering by club IDs', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('athlete.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->athletes(clubs: [12345, 67890]);

            $mockClient->assertSent(function (GetAthletesRequest $request): bool {
                $query = $request->query()->all();

                return $query['clubs'] === '12345,67890';
            });
        });

        it('supports filtering by levels', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->athletes(levels: ['WLEVEL04', 'WLEVEL05']);

            $mockClient->assertSent(function (GetAthletesRequest $request): bool {
                $query = $request->query()->all();

                return $query['levels'] === 'WLEVEL04,WLEVEL05';
            });
        });

        it('returns empty array when no athletes are found', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector->sanctions(58025)->reservations()->athletes();

            expect($athletes)->toBeArray()
                ->and($athletes)->toBeEmpty();
        });
    });

    describe('coaches() method', function () {
        it('fetches coach reservations for a sanction', function () {
            $mockClient = new MockClient([
                GetCoachesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('coach.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $coaches = $connector->sanctions(58025)->reservations()->coaches();

            expect($coaches)->toBeArray()
                ->and($coaches)->toHaveCount(1);
        });

        it('returns an array of CoachReservation objects', function () {
            $mockClient = new MockClient([
                GetCoachesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('coach.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $coaches = $connector->sanctions(58025)->reservations()->coaches();

            expect($coaches[0])->toBeInstanceOf(CoachReservation::class);
        });

        it('correctly maps coach properties', function () {
            $mockClient = new MockClient([
                GetCoachesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('coach.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $coaches = $connector->sanctions(58025)->reservations()->coaches();
            $coach = $coaches[0];

            expect($coach->memberId)->toBe('555555')
                ->and($coach->firstName)->toBe('Coach')
                ->and($coach->lastName)->toBe('Williams')
                ->and($coach->clubName)->toBe('ABC Gymnastics');
        });

        it('supports filtering by club IDs', function () {
            $mockClient = new MockClient([
                GetCoachesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->coaches(clubs: [12345]);

            $mockClient->assertSent(function (GetCoachesRequest $request): bool {
                $query = $request->query()->all();

                return $query['clubs'] === '12345';
            });
        });
    });

    describe('judges() method', function () {
        it('fetches judge reservations for a sanction', function () {
            $mockClient = new MockClient([
                GetJudgesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('judge.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $judges = $connector->sanctions(58025)->reservations()->judges();

            expect($judges)->toBeArray()
                ->and($judges)->toHaveCount(1);
        });

        it('returns an array of JudgeReservation objects', function () {
            $mockClient = new MockClient([
                GetJudgesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('judge.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $judges = $connector->sanctions(58025)->reservations()->judges();

            expect($judges[0])->toBeInstanceOf(JudgeReservation::class);
        });

        it('correctly maps judge properties', function () {
            $mockClient = new MockClient([
                GetJudgesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('judge.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $judges = $connector->sanctions(58025)->reservations()->judges();
            $judge = $judges[0];

            expect($judge->memberId)->toBe('666666')
                ->and($judge->firstName)->toBe('Judge')
                ->and($judge->lastName)->toBe('Davis')
                ->and($judge->level)->toBe('National')
                ->and($judge->certifications)->toContain('NAT', 'REG');
        });

        it('judges can check certification level', function () {
            $mockClient = new MockClient([
                GetJudgesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('judge.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $judges = $connector->sanctions(58025)->reservations()->judges();
            $judge = $judges[0];

            expect($judge->hasCertification('NAT'))->toBeTrue()
                ->and($judge->hasCertification('INT'))->toBeFalse();
        });
    });

    describe('clubs() method', function () {
        it('fetches club reservations for a sanction', function () {
            $mockClient = new MockClient([
                GetClubsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('club.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $clubs = $connector->sanctions(58025)->reservations()->clubs();

            expect($clubs)->toBeArray()
                ->and($clubs)->toHaveCount(1);
        });

        it('returns an array of ClubReservation objects', function () {
            $mockClient = new MockClient([
                GetClubsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('club.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $clubs = $connector->sanctions(58025)->reservations()->clubs();

            expect($clubs[0])->toBeInstanceOf(ClubReservation::class);
        });

        it('correctly maps club properties', function () {
            $mockClient = new MockClient([
                GetClubsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('club.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $clubs = $connector->sanctions(58025)->reservations()->clubs();
            $club = $clubs[0];

            expect($club->clubId)->toBe('12345')
                ->and($club->clubAbbrev)->toBe('ABC')
                ->and($club->clubName)->toBe('ABC Gymnastics')
                ->and($club->clubCity)->toBe('Los Angeles')
                ->and($club->clubState)->toBe('CA')
                ->and($club->internationalClub)->toBeFalse();
        });

        it('supports filtering by club IDs', function () {
            $mockClient = new MockClient([
                GetClubsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->clubs(clubs: [12345, 67890]);

            $mockClient->assertSent(function (GetClubsRequest $request): bool {
                $query = $request->query()->all();

                return $query['clubs'] === '12345,67890';
            });
        });

        it('club has displayName helper method', function () {
            $mockClient = new MockClient([
                GetClubsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('club.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $clubs = $connector->sanctions(58025)->reservations()->clubs();
            $club = $clubs[0];

            expect($club->displayName())->toBe('ABC');
        });

        it('club has location helper method', function () {
            $mockClient = new MockClient([
                GetClubsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('club.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $clubs = $connector->sanctions(58025)->reservations()->clubs();
            $club = $clubs[0];

            expect($club->location())->toBe('Los Angeles, CA');
        });
    });

    describe('groups() method', function () {
        it('fetches group reservations for a sanction', function () {
            $mockClient = new MockClient([
                GetGroupsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('group.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $groups = $connector->sanctions(58025)->reservations()->groups();

            expect($groups)->toBeArray()
                ->and($groups)->toHaveCount(1);
        });

        it('returns an array of GroupReservation objects', function () {
            $mockClient = new MockClient([
                GetGroupsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('group.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $groups = $connector->sanctions(58025)->reservations()->groups();

            expect($groups[0])->toBeInstanceOf(GroupReservation::class);
        });

        it('correctly maps group properties', function () {
            $mockClient = new MockClient([
                GetGroupsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('group.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $groups = $connector->sanctions(58025)->reservations()->groups();
            $group = $groups[0];

            expect($group->groupId)->toBe('GRP001')
                ->and($group->groupName)->toBe('ABC Junior Group')
                ->and($group->groupType)->toBe('Group')
                ->and($group->apparatus)->toBe('5 Balls')
                ->and($group->level)->toBe('Level 7')
                ->and($group->ageGroup)->toBe('Junior');
        });

        it('correctly maps group athletes', function () {
            $mockClient = new MockClient([
                GetGroupsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [loadFixture('group.json')],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $groups = $connector->sanctions(58025)->reservations()->groups();
            $group = $groups[0];

            expect($group->athletes)->toBeArray()
                ->and($group->athletes)->toHaveCount(5)
                ->and($group->athleteCount())->toBe(5);
        });

        it('supports filtering by club IDs and levels', function () {
            $mockClient = new MockClient([
                GetGroupsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->groups(
                clubs: [12345],
                levels: ['Level 7', 'Level 8']
            );

            $mockClient->assertSent(function (GetGroupsRequest $request): bool {
                $query = $request->query()->all();

                return $query['clubs'] === '12345'
                    && $query['levels'] === 'Level 7,Level 8';
            });
        });
    });

    describe('individuals() method', function () {
        it('fetches both athletes and coaches for a sanction', function () {
            $mockClient = new MockClient([
                GetIndividualsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [
                            loadFixture('athlete.json'),
                            array_merge(loadFixture('coach.json'), ['Level' => 'Coach']),
                        ],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $individuals = $connector->sanctions(58025)->reservations()->individuals();

            expect($individuals)->toBeArray()
                ->and($individuals)->toHaveKeys(['athletes', 'coaches']);
        });

        it('separates athletes and coaches correctly', function () {
            $mockClient = new MockClient([
                GetIndividualsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => [
                            loadFixture('athlete.json'),
                            array_merge(loadFixture('coach.json'), ['Level' => 'Coach']),
                        ],
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $individuals = $connector->sanctions(58025)->reservations()->individuals();

            expect($individuals['athletes'])->toHaveCount(1)
                ->and($individuals['coaches'])->toHaveCount(1)
                ->and($individuals['athletes'][0])->toBeInstanceOf(AthleteReservation::class)
                ->and($individuals['coaches'][0])->toBeInstanceOf(CoachReservation::class);
        });

        it('supports filtering by clubs and levels', function () {
            $mockClient = new MockClient([
                GetIndividualsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->individuals(
                clubs: [12345],
                levels: ['WLEVEL04']
            );

            $mockClient->assertSent(function (GetIndividualsRequest $request): bool {
                $query = $request->query()->all();

                return $query['clubs'] === '12345'
                    && $query['levels'] === 'WLEVEL04';
            });
        });
    });

    describe('athleteCount() method', function () {
        it('returns count of unique athletes for a club', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => [
                        'reservations' => loadFixture('athletes.json'),
                    ],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $count = $connector->sanctions(58025)->reservations()->athleteCount(12345);

            expect($count)->toBe(2);
        });

        it('returns 0 when no athletes found', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $count = $connector->sanctions(58025)->reservations()->athleteCount(99999);

            expect($count)->toBe(0);
        });

        it('counts unique member IDs only', function () {
            // Create duplicate athletes with same member ID
            $athleteData = loadFixture('athlete.json');
            $duplicateAthletes = [
                $athleteData,
                $athleteData, // Same member ID
                array_merge($athleteData, ['MemberID' => '111111']), // Different member ID
            ];

            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => $duplicateAthletes],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $count = $connector->sanctions(58025)->reservations()->athleteCount(12345);

            expect($count)->toBe(2); // Should count unique IDs only
        });
    });

    describe('fluent API chain', function () {
        it('supports full fluent chain from connector to athletes', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => loadFixture('athletes.json')],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector
                ->sanctions(58025)
                ->reservations()
                ->athletes();

            expect($athletes)->toBeArray()
                ->and($athletes)->toHaveCount(2);
        });

        it('maintains correct sanction ID throughout the chain', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => []],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $connector->sanctions(58025)->reservations()->athletes();

            $mockClient->assertSent(function (GetAthletesRequest $request): bool {
                return $request->resolveEndpoint() === '/sanction/58025/reservations/athlete';
            });
        });
    });

    describe('DTO helper methods', function () {
        it('athlete has fullName method', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('athlete.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector->sanctions(58025)->reservations()->athletes();

            expect($athletes[0]->fullName())->toBe('Jane Smith');
        });

        it('athlete has canCompete method', function () {
            $mockClient = new MockClient([
                GetAthletesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('athlete.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $athletes = $connector->sanctions(58025)->reservations()->athletes();

            expect($athletes[0]->canCompete())->toBeTrue();
        });

        it('coach has fullName method', function () {
            $mockClient = new MockClient([
                GetCoachesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('coach.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $coaches = $connector->sanctions(58025)->reservations()->coaches();

            expect($coaches[0]->fullName())->toBe('Coach Williams');
        });

        it('judge has fullName method', function () {
            $mockClient = new MockClient([
                GetJudgesRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('judge.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $judges = $connector->sanctions(58025)->reservations()->judges();

            expect($judges[0]->fullName())->toBe('Judge Davis');
        });

        it('group has canCompete method', function () {
            $mockClient = new MockClient([
                GetGroupsRequest::class => MockResponse::make([
                    'status' => 'success',
                    'data' => ['reservations' => [loadFixture('group.json')]],
                ], 200),
            ]);

            $connector = new UsaGym('test-user', 'test-pass');
            $connector->withMockClient($mockClient);

            $groups = $connector->sanctions(58025)->reservations()->groups();

            expect($groups[0]->canCompete())->toBeTrue();
        });
    });
});
