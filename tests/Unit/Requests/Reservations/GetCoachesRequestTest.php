<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Reservations\GetCoachesRequest;
use AustinW\UsaGym\Data\CoachReservation;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('GetCoachesRequest', function () {
    it('uses GET method', function () {
        $request = new GetCoachesRequest(58025);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID', function () {
        $request = new GetCoachesRequest(58025);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/reservations/coach');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new GetCoachesRequest(12345);

        expect($request->resolveEndpoint())->toBe('/sanction/12345/reservations/coach');
    });
});

describe('GetCoachesRequest query parameters', function () {
    it('has no query parameters when no filters provided', function () {
        $request = new GetCoachesRequest(58025);

        expect($request->query()->all())->toBe([]);
    });

    it('builds query with clubs filter as comma-separated string', function () {
        $request = new GetCoachesRequest(58025, clubs: [123, 456, 789]);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('123,456,789');
    });

    it('builds query with single club filter', function () {
        $request = new GetCoachesRequest(58025, clubs: [100]);

        $query = $request->query()->all();

        expect($query['clubs'])->toBe('100');
    });

    it('does not include clubs key when clubs array is empty', function () {
        $request = new GetCoachesRequest(58025, clubs: []);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('does not include clubs key when clubs is null', function () {
        $request = new GetCoachesRequest(58025, clubs: null);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });
});

describe('GetCoachesRequest::createDtoFromResponse', function () {
    it('creates array of CoachReservation from response', function () {
        $mockClient = new MockClient([
            GetCoachesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '12345',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gymnastics',
                            'InternationalClub' => false,
                            'MemberID' => '67890',
                            'LastName' => 'Johnson',
                            'FirstName' => 'Mike',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'InternationalMember' => false,
                            'Status' => 'Active',
                            'RegDate' => '2024-01-15 09:30:00',
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

        $result = $connector->send(new GetCoachesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(CoachReservation::class)
            ->and($result[0]->memberId)->toBe('67890')
            ->and($result[0]->lastName)->toBe('Johnson')
            ->and($result[0]->firstName)->toBe('Mike')
            ->and($result[0]->clubName)->toBe('ABC Gymnastics');
    });

    it('creates multiple CoachReservation objects', function () {
        $mockClient = new MockClient([
            GetCoachesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'OrgID' => '111',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gym',
                            'MemberID' => '001',
                            'LastName' => 'Coach1',
                            'FirstName' => 'First',
                            'Discipline' => 'WAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                        ],
                        [
                            'OrgID' => '222',
                            'ClubAbbrev' => 'XYZ',
                            'ClubName' => 'XYZ Gym',
                            'MemberID' => '002',
                            'LastName' => 'Coach2',
                            'FirstName' => 'Second',
                            'Discipline' => 'MAG',
                            'MemberType' => 'CCOACH',
                            'Status' => 'Active',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetCoachesRequest(58025))->dto();

        expect($result)->toHaveCount(2)
            ->and($result[0]->memberId)->toBe('001')
            ->and($result[0]->lastName)->toBe('Coach1')
            ->and($result[1]->memberId)->toBe('002')
            ->and($result[1]->lastName)->toBe('Coach2');
    });

    it('returns empty array when no reservations in response', function () {
        $mockClient = new MockClient([
            GetCoachesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetCoachesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when reservations key is missing', function () {
        $mockClient = new MockClient([
            GetCoachesRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetCoachesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
