<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Reservations\GetIndividualsRequest;
use AustinW\UsaGym\Data\AthleteReservation;
use AustinW\UsaGym\Data\CoachReservation;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

// Create a test enum for level filtering
enum IndividualTestLevel: string
{
    case Level5 = 'L5';
    case Level6 = 'L6';
    case Level7 = 'L7';
}

describe('GetIndividualsRequest', function () {
    it('uses GET method', function () {
        $request = new GetIndividualsRequest(58025);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID', function () {
        $request = new GetIndividualsRequest(58025);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/reservations/individual');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new GetIndividualsRequest(55555);

        expect($request->resolveEndpoint())->toBe('/sanction/55555/reservations/individual');
    });
});

describe('GetIndividualsRequest query parameters', function () {
    it('has no query parameters when no filters provided', function () {
        $request = new GetIndividualsRequest(58025);

        expect($request->query()->all())->toBe([]);
    });

    it('builds query with clubs filter as comma-separated string', function () {
        $request = new GetIndividualsRequest(58025, clubs: [100, 200, 300]);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('100,200,300');
    });

    it('builds query with single club filter', function () {
        $request = new GetIndividualsRequest(58025, clubs: [777]);

        $query = $request->query()->all();

        expect($query['clubs'])->toBe('777');
    });

    it('does not include clubs key when clubs array is empty', function () {
        $request = new GetIndividualsRequest(58025, clubs: []);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('does not include clubs key when clubs is null', function () {
        $request = new GetIndividualsRequest(58025, clubs: null);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('builds query with levels filter as comma-separated string', function () {
        $request = new GetIndividualsRequest(58025, levels: ['L5', 'L6', 'L7']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('levels')
            ->and($query['levels'])->toBe('L5,L6,L7');
    });

    it('builds query with BackedEnum levels', function () {
        $request = new GetIndividualsRequest(58025, levels: [IndividualTestLevel::Level5, IndividualTestLevel::Level7]);

        $query = $request->query()->all();

        expect($query['levels'])->toBe('L5,L7');
    });

    it('builds query with mixed string and BackedEnum levels', function () {
        $request = new GetIndividualsRequest(58025, levels: [IndividualTestLevel::Level6, 'ELITE', IndividualTestLevel::Level5]);

        $query = $request->query()->all();

        expect($query['levels'])->toBe('L6,ELITE,L5');
    });

    it('does not include levels key when levels array is empty', function () {
        $request = new GetIndividualsRequest(58025, levels: []);

        expect($request->query()->all())->not->toHaveKey('levels');
    });

    it('builds query with both clubs and levels filters', function () {
        $request = new GetIndividualsRequest(58025, clubs: [111, 222], levels: ['L8', 'L9']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('111,222')
            ->and($query)->toHaveKey('levels')
            ->and($query['levels'])->toBe('L8,L9');
    });
});

describe('GetIndividualsRequest::createDtoFromResponse', function () {
    it('creates separate arrays for athletes and coaches', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => '001',
                            'LastName' => 'Athlete1',
                            'FirstName' => 'First',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'Status' => 'Active',
                            'Level' => 'Level 5',
                        ],
                        [
                            'OrgID' => '222',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => '002',
                            'LastName' => 'Coach1',
                            'FirstName' => 'Head',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                            'Level' => 'Coach',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('athletes')
            ->and($result)->toHaveKey('coaches')
            ->and($result['athletes'])->toHaveCount(1)
            ->and($result['coaches'])->toHaveCount(1)
            ->and($result['athletes'][0])->toBeInstanceOf(AthleteReservation::class)
            ->and($result['coaches'][0])->toBeInstanceOf(CoachReservation::class);
    });

    it('identifies coaches by Level = Coach', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'XYZ',
                            'ClubName' => 'XYZ Gym',
                            'MemberID' => '001',
                            'LastName' => 'CoachPerson',
                            'FirstName' => 'Some',
                            'Discipline' => 'MAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                            'Level' => 'Coach',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result['coaches'])->toHaveCount(1)
            ->and($result['athletes'])->toBeEmpty();
    });

    it('identifies coaches by ReservationType = coach', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'DEF',
                            'ClubName' => 'DEF Gym',
                            'MemberID' => '001',
                            'LastName' => 'AnotherCoach',
                            'FirstName' => 'Another',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                            'Level' => '',
                            'ReservationType' => 'coach',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result['coaches'])->toHaveCount(1)
            ->and($result['athletes'])->toBeEmpty();
    });

    it('correctly separates multiple athletes and coaches', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => 'A001',
                            'LastName' => 'Athlete1',
                            'FirstName' => 'First',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'Status' => 'Active',
                            'Level' => 'L5',
                        ],
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => 'C001',
                            'LastName' => 'Coach1',
                            'FirstName' => 'Head',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                            'Level' => 'Coach',
                        ],
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => 'A002',
                            'LastName' => 'Athlete2',
                            'FirstName' => 'Second',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'Status' => 'Active',
                            'Level' => 'L6',
                        ],
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => 'C002',
                            'LastName' => 'Coach2',
                            'FirstName' => 'Assistant',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                            'Level' => 'Coach',
                        ],
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => 'A003',
                            'LastName' => 'Athlete3',
                            'FirstName' => 'Third',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'Status' => 'Active',
                            'Level' => 'L7',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result['athletes'])->toHaveCount(3)
            ->and($result['coaches'])->toHaveCount(2)
            ->and($result['athletes'][0]->memberId)->toBe('A001')
            ->and($result['athletes'][1]->memberId)->toBe('A002')
            ->and($result['athletes'][2]->memberId)->toBe('A003')
            ->and($result['coaches'][0]->memberId)->toBe('C001')
            ->and($result['coaches'][1]->memberId)->toBe('C002');
    });

    it('returns empty arrays when only athletes present', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => '001',
                            'LastName' => 'OnlyAthlete',
                            'FirstName' => 'Solo',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'Status' => 'Active',
                            'Level' => 'Level 5',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result['athletes'])->toHaveCount(1)
            ->and($result['coaches'])->toBeEmpty();
    });

    it('returns empty arrays when only coaches present', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => '001',
                            'LastName' => 'OnlyCoach',
                            'FirstName' => 'Solo',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                            'Level' => 'Coach',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result['athletes'])->toBeEmpty()
            ->and($result['coaches'])->toHaveCount(1);
    });

    it('returns empty arrays when no reservations in response', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result['athletes'])->toBeEmpty()
            ->and($result['coaches'])->toBeEmpty();
    });

    it('returns empty arrays when reservations key is missing', function () {
        $mockClient = new MockClient([
            GetIndividualsRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetIndividualsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result['athletes'])->toBeEmpty()
            ->and($result['coaches'])->toBeEmpty();
    });
});
