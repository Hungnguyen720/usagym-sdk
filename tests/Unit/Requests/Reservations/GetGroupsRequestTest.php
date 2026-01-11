<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Reservations\GetGroupsRequest;
use AustinW\UsaGym\Data\GroupReservation;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

// Create a test enum for level filtering
enum GroupTestLevel: string
{
    case Novice = 'NOV';
    case Intermediate = 'INT';
    case Advanced = 'ADV';
}

describe('GetGroupsRequest', function () {
    it('uses GET method', function () {
        $request = new GetGroupsRequest(58025);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID', function () {
        $request = new GetGroupsRequest(58025);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/reservations/group');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new GetGroupsRequest(33333);

        expect($request->resolveEndpoint())->toBe('/sanction/33333/reservations/group');
    });
});

describe('GetGroupsRequest query parameters', function () {
    it('has no query parameters when no filters provided', function () {
        $request = new GetGroupsRequest(58025);

        expect($request->query()->all())->toBe([]);
    });

    it('builds query with clubs filter as comma-separated string', function () {
        $request = new GetGroupsRequest(58025, clubs: [111, 222, 333]);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('111,222,333');
    });

    it('builds query with single club filter', function () {
        $request = new GetGroupsRequest(58025, clubs: [999]);

        $query = $request->query()->all();

        expect($query['clubs'])->toBe('999');
    });

    it('does not include clubs key when clubs array is empty', function () {
        $request = new GetGroupsRequest(58025, clubs: []);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('does not include clubs key when clubs is null', function () {
        $request = new GetGroupsRequest(58025, clubs: null);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('builds query with levels filter as comma-separated string', function () {
        $request = new GetGroupsRequest(58025, levels: ['NOV', 'INT', 'ADV']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('levels')
            ->and($query['levels'])->toBe('NOV,INT,ADV');
    });

    it('builds query with BackedEnum levels', function () {
        $request = new GetGroupsRequest(58025, levels: [GroupTestLevel::Novice, GroupTestLevel::Advanced]);

        $query = $request->query()->all();

        expect($query['levels'])->toBe('NOV,ADV');
    });

    it('builds query with mixed string and BackedEnum levels', function () {
        $request = new GetGroupsRequest(58025, levels: [GroupTestLevel::Intermediate, 'CUSTOM', GroupTestLevel::Novice]);

        $query = $request->query()->all();

        expect($query['levels'])->toBe('INT,CUSTOM,NOV');
    });

    it('does not include levels key when levels array is empty', function () {
        $request = new GetGroupsRequest(58025, levels: []);

        expect($request->query()->all())->not->toHaveKey('levels');
    });

    it('builds query with both clubs and levels filters', function () {
        $request = new GetGroupsRequest(58025, clubs: [100, 200], levels: ['NOV', 'INT']);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('100,200')
            ->and($query)->toHaveKey('levels')
            ->and($query['levels'])->toBe('NOV,INT');
    });
});

describe('GetGroupsRequest::createDtoFromResponse', function () {
    it('creates array of GroupReservation from response', function () {
        $mockClient = new MockClient([
            GetGroupsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '12345',
                            'ClubAbbrev' => 'RGC',
                            'ClubName' => 'Rhythmic Gymnastics Club',
                            'InternationalClub' => false,
                            'GroupID' => '67890',
                            'GroupName' => 'Junior Group A',
                            'GroupType' => 'Group',
                            'Discipline' => 'RG',
                            'Status' => 'Active',
                            'RegDate' => '2024-01-20 11:00:00',
                            'Apparatus' => '5 Balls',
                            'Level' => 'Level 8',
                            'AgeGroup' => 'Junior',
                            'Athletes' => [
                                [
                                    'MemberID' => '001',
                                    'FirstName' => 'Emma',
                                    'LastName' => 'Johnson',
                                ],
                                [
                                    'MemberID' => '002',
                                    'FirstName' => 'Olivia',
                                    'LastName' => 'Smith',
                                ],
                            ],
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

        $result = $connector->send(new GetGroupsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(GroupReservation::class)
            ->and($result[0]->groupId)->toBe('67890')
            ->and($result[0]->groupName)->toBe('Junior Group A')
            ->and($result[0]->groupType)->toBe('Group')
            ->and($result[0]->clubName)->toBe('Rhythmic Gymnastics Club')
            ->and($result[0]->level)->toBe('Level 8')
            ->and($result[0]->apparatus)->toBe('5 Balls')
            ->and($result[0]->athletes)->toHaveCount(2);
    });

    it('creates multiple GroupReservation objects', function () {
        $mockClient = new MockClient([
            GetGroupsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ACRO1',
                            'ClubName' => 'Acro Club 1',
                            'GroupID' => 'G001',
                            'GroupName' => 'Pair A',
                            'GroupType' => 'Pair',
                            'Discipline' => 'ACRO',
                            'Status' => 'Active',
                            'Level' => 'Level 7',
                            'Athletes' => [],
                        ],
                        [
                            'OrgID' => '222',
                            'ClubAbbrev' => 'ACRO2',
                            'ClubName' => 'Acro Club 2',
                            'GroupID' => 'G002',
                            'GroupName' => 'Trio B',
                            'GroupType' => 'Trio',
                            'Discipline' => 'ACRO',
                            'Status' => 'Active',
                            'Level' => 'Level 8',
                            'Athletes' => [],
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetGroupsRequest(58025))->dto();

        expect($result)->toHaveCount(2)
            ->and($result[0]->groupId)->toBe('G001')
            ->and($result[0]->groupType)->toBe('Pair')
            ->and($result[1]->groupId)->toBe('G002')
            ->and($result[1]->groupType)->toBe('Trio');
    });

    it('handles group without athletes array', function () {
        $mockClient = new MockClient([
            GetGroupsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '12345',
                            'ClubAbbrev' => 'GRP',
                            'ClubName' => 'Group Club',
                            'GroupID' => '67890',
                            'GroupName' => 'Empty Group',
                            'GroupType' => 'Group',
                            'Discipline' => 'RG',
                            'Status' => 'Active',
                            'Level' => 'Level 6',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetGroupsRequest(58025))->dto();

        expect($result[0]->athletes)->toBe([]);
    });

    it('returns empty array when no reservations in response', function () {
        $mockClient = new MockClient([
            GetGroupsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetGroupsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when reservations key is missing', function () {
        $mockClient = new MockClient([
            GetGroupsRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetGroupsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
