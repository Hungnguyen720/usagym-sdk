<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Reservations\GetAthletesRequest;
use AustinW\UsaGym\Data\AthleteReservation;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

// Create a test enum for level filtering
enum TestLevel: string
{
    case Level1 = 'L1';
    case Level2 = 'L2';
    case Elite = 'ELITE';
}

describe('GetAthletesRequest', function () {
    it('uses GET method', function () {
        $request = new GetAthletesRequest(58025);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID', function () {
        $request = new GetAthletesRequest(58025);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/reservations/athlete');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new GetAthletesRequest(99999);

        expect($request->resolveEndpoint())->toBe('/sanction/99999/reservations/athlete');
    });
});

describe('GetAthletesRequest query parameters', function () {
    it('has no query parameters when no filters provided', function () {
        $request = new GetAthletesRequest(58025);

        expect($request->query()->all())->toBe([]);
    });

    it('builds query with clubs filter as comma-separated string', function () {
        $request = new GetAthletesRequest(58025, clubs: [123, 456, 789]);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('123,456,789');
    });

    it('builds query with single club filter', function () {
        $request = new GetAthletesRequest(58025, clubs: [123]);

        $query = $request->query()->all();

        expect($query['clubs'])->toBe('123');
    });

    it('does not include clubs key when clubs array is empty', function () {
        $request = new GetAthletesRequest(58025, clubs: []);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('does not include clubs key when clubs is null', function () {
        $request = new GetAthletesRequest(58025, clubs: null);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('builds query with levels filter as comma-separated string', function () {
        $request = new GetAthletesRequest(58025, levels: ['L1', 'L2', 'L3']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('levels')
            ->and($query['levels'])->toBe('L1,L2,L3');
    });

    it('builds query with BackedEnum levels', function () {
        $request = new GetAthletesRequest(58025, levels: [TestLevel::Level1, TestLevel::Elite]);

        $query = $request->query()->all();

        expect($query['levels'])->toBe('L1,ELITE');
    });

    it('builds query with mixed string and BackedEnum levels', function () {
        $request = new GetAthletesRequest(58025, levels: [TestLevel::Level1, 'CustomLevel', TestLevel::Level2]);

        $query = $request->query()->all();

        expect($query['levels'])->toBe('L1,CustomLevel,L2');
    });

    it('does not include levels key when levels array is empty', function () {
        $request = new GetAthletesRequest(58025, levels: []);

        expect($request->query()->all())->not->toHaveKey('levels');
    });

    it('builds query with both clubs and levels filters', function () {
        $request = new GetAthletesRequest(58025, clubs: [123, 456], levels: ['L5', 'L6']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('123,456')
            ->and($query)->toHaveKey('levels')
            ->and($query['levels'])->toBe('L5,L6');
    });
});

describe('GetAthletesRequest::createDtoFromResponse', function () {
    it('creates array of AthleteReservation from response', function () {
        $mockClient = new MockClient([
            GetAthletesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '12345',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gymnastics',
                            'InternationalClub' => false,
                            'MemberID' => '67890',
                            'LastName' => 'Smith',
                            'FirstName' => 'Jane',
                            'DOB' => '01/15/2010',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'InternationalMember' => false,
                            'Status' => 'Active',
                            'RegDate' => '2024-01-01 10:00:00',
                            'Apparatus' => null,
                            'Level' => 'Level 5',
                            'AgeGroup' => 'Junior',
                            'Scratched' => false,
                            'ScratchDate' => null,
                            'ModifiedDate' => null,
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetAthletesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(AthleteReservation::class)
            ->and($result[0]->memberId)->toBe('67890')
            ->and($result[0]->lastName)->toBe('Smith')
            ->and($result[0]->firstName)->toBe('Jane')
            ->and($result[0]->clubName)->toBe('ABC Gymnastics')
            ->and($result[0]->level)->toBe('Level 5');
    });

    it('creates multiple AthleteReservation objects', function () {
        $mockClient = new MockClient([
            GetAthletesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => '001',
                            'LastName' => 'One',
                            'FirstName' => 'Athlete',
                            'Discipline' => 'WAG',
                            'MemberType' => 'ATHL',
                            'Status' => 'Active',
                            'Level' => 'L5',
                        ],
                        [
                            'OrgID' => '222',
                            'ClubAbbrev' => 'XYZ',
                            'ClubName' => 'XYZ Gym',
                            'MemberID' => '002',
                            'LastName' => 'Two',
                            'FirstName' => 'Athlete',
                            'Discipline' => 'MAG',
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

        $result = $connector->send(new GetAthletesRequest(58025))->dto();

        expect($result)->toHaveCount(2)
            ->and($result[0]->memberId)->toBe('001')
            ->and($result[1]->memberId)->toBe('002');
    });

    it('returns empty array when no reservations in response', function () {
        $mockClient = new MockClient([
            GetAthletesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetAthletesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when reservations key is missing', function () {
        $mockClient = new MockClient([
            GetAthletesRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetAthletesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
