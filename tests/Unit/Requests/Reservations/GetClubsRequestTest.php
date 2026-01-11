<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Reservations\GetClubsRequest;
use AustinW\UsaGym\Data\ClubReservation;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('GetClubsRequest', function () {
    it('uses GET method', function () {
        $request = new GetClubsRequest(58025);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID', function () {
        $request = new GetClubsRequest(58025);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/reservations/clubs');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new GetClubsRequest(44444);

        expect($request->resolveEndpoint())->toBe('/sanction/44444/reservations/clubs');
    });
});

describe('GetClubsRequest query parameters', function () {
    it('has no query parameters when no filters provided', function () {
        $request = new GetClubsRequest(58025);

        expect($request->query()->all())->toBe([]);
    });

    it('builds query with clubs filter as comma-separated string', function () {
        $request = new GetClubsRequest(58025, clubs: [100, 200, 300]);

        $query = $request->query()->all();

        expect($query)->toHaveKey('clubs')
            ->and($query['clubs'])->toBe('100,200,300');
    });

    it('builds query with single club filter', function () {
        $request = new GetClubsRequest(58025, clubs: [555]);

        $query = $request->query()->all();

        expect($query['clubs'])->toBe('555');
    });

    it('does not include clubs key when clubs array is empty', function () {
        $request = new GetClubsRequest(58025, clubs: []);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });

    it('does not include clubs key when clubs is null', function () {
        $request = new GetClubsRequest(58025, clubs: null);

        expect($request->query()->all())->not->toHaveKey('clubs');
    });
});

describe('GetClubsRequest::createDtoFromResponse', function () {
    it('creates array of ClubReservation from response', function () {
        $mockClient = new MockClient([
            GetClubsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'ClubID' => '12345',
                            'ClubAbbrev' => 'ABC',
                            'ClubName' => 'ABC Gymnastics Academy',
                            'ClubCity' => 'Springfield',
                            'ClubState' => 'IL',
                            'ClubContactID' => '99999',
                            'ClubContactName' => 'John Doe',
                            'ClubContactEmail' => 'john@abc-gym.com',
                            'ClubContactPhone' => '555-123-4567',
                            'MeetContactID' => '88888',
                            'MeetContactName' => 'Jane Smith',
                            'MeetContactEmail' => 'jane@abc-gym.com',
                            'MeetContactPhone' => '555-987-6543',
                            'InternationalClub' => false,
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetClubsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(ClubReservation::class)
            ->and($result[0]->clubId)->toBe('12345')
            ->and($result[0]->clubAbbrev)->toBe('ABC')
            ->and($result[0]->clubName)->toBe('ABC Gymnastics Academy')
            ->and($result[0]->clubCity)->toBe('Springfield')
            ->and($result[0]->clubState)->toBe('IL')
            ->and($result[0]->clubContactEmail)->toBe('john@abc-gym.com')
            ->and($result[0]->meetContactName)->toBe('Jane Smith');
    });

    it('creates multiple ClubReservation objects', function () {
        $mockClient = new MockClient([
            GetClubsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'ClubID' => '001',
                            'ClubAbbrev' => 'CLUB1',
                            'ClubName' => 'First Club',
                            'ClubCity' => 'City1',
                            'ClubState' => 'CA',
                            'InternationalClub' => false,
                        ],
                        [
                            'ClubID' => '002',
                            'ClubAbbrev' => 'CLUB2',
                            'ClubName' => 'Second Club',
                            'ClubCity' => 'City2',
                            'ClubState' => 'TX',
                            'InternationalClub' => false,
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetClubsRequest(58025))->dto();

        expect($result)->toHaveCount(2)
            ->and($result[0]->clubId)->toBe('001')
            ->and($result[0]->clubName)->toBe('First Club')
            ->and($result[1]->clubId)->toBe('002')
            ->and($result[1]->clubName)->toBe('Second Club');
    });

    it('handles club with international flag', function () {
        $mockClient = new MockClient([
            GetClubsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'ClubID' => '99999',
                            'ClubAbbrev' => 'INTL',
                            'ClubName' => 'International Gym',
                            'InternationalClub' => true,
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetClubsRequest(58025))->dto();

        expect($result[0]->internationalClub)->toBeTrue();
    });

    it('handles club with null optional fields', function () {
        $mockClient = new MockClient([
            GetClubsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'ClubID' => '12345',
                            'ClubAbbrev' => '',
                            'ClubName' => 'Minimal Club',
                            'ClubCity' => '',
                            'ClubState' => '',
                            'ClubContactID' => '',
                            'ClubContactName' => null,
                            'ClubContactEmail' => '',
                            'ClubContactPhone' => '',
                            'MeetContactID' => '',
                            'MeetContactName' => '',
                            'MeetContactEmail' => '',
                            'MeetContactPhone' => '',
                            'InternationalClub' => false,
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetClubsRequest(58025))->dto();

        expect($result[0]->clubAbbrev)->toBeNull()
            ->and($result[0]->clubCity)->toBeNull()
            ->and($result[0]->clubState)->toBeNull();
    });

    it('returns empty array when no reservations in response', function () {
        $mockClient = new MockClient([
            GetClubsRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetClubsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when reservations key is missing', function () {
        $mockClient = new MockClient([
            GetClubsRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetClubsRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
