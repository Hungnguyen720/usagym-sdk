<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Reservations\GetJudgesRequest;
use AustinW\UsaGym\Data\JudgeReservation;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('GetJudgesRequest', function () {
    it('uses GET method', function () {
        $request = new GetJudgesRequest(58025);

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint with sanction ID', function () {
        $request = new GetJudgesRequest(58025);

        expect($request->resolveEndpoint())->toBe('/sanction/58025/reservations/judge');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new GetJudgesRequest(77777);

        expect($request->resolveEndpoint())->toBe('/sanction/77777/reservations/judge');
    });

    it('has no query parameters', function () {
        $request = new GetJudgesRequest(58025);

        expect($request->query()->all())->toBe([]);
    });
});

describe('GetJudgesRequest::createDtoFromResponse', function () {
    it('creates array of JudgeReservation from response', function () {
        $mockClient = new MockClient([
            GetJudgesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'MemberID' => '67890',
                            'LastName' => 'Williams',
                            'FirstName' => 'Sarah',
                            'Discipline' => 'WAG',
                            'MemberType' => 'JUDGE',
                            'InternationalMember' => false,
                            'Status' => 'Active',
                            'RegDate' => '2024-02-01 14:00:00',
                            'Level' => 'National',
                            'Scratched' => false,
                            'ScratchDate' => null,
                            'ModifiedDate' => null,
                            'Certification' => ['NAT', 'REG'],
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetJudgesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(JudgeReservation::class)
            ->and($result[0]->memberId)->toBe('67890')
            ->and($result[0]->lastName)->toBe('Williams')
            ->and($result[0]->firstName)->toBe('Sarah')
            ->and($result[0]->level)->toBe('National')
            ->and($result[0]->certifications)->toBe(['NAT', 'REG']);
    });

    it('creates multiple JudgeReservation objects', function () {
        $mockClient = new MockClient([
            GetJudgesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'MemberID' => '001',
                            'LastName' => 'Judge1',
                            'FirstName' => 'First',
                            'Discipline' => 'WAG',
                            'MemberType' => 'JUDGE',
                            'Status' => 'Active',
                            'Level' => 'Regional',
                            'Certification' => ['REG'],
                        ],
                        [
                            'MemberID' => '002',
                            'LastName' => 'Judge2',
                            'FirstName' => 'Second',
                            'Discipline' => 'MAG',
                            'MemberType' => 'JUDGE',
                            'Status' => 'Active',
                            'Level' => 'State',
                            'Certification' => ['STATE'],
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetJudgesRequest(58025))->dto();

        expect($result)->toHaveCount(2)
            ->and($result[0]->memberId)->toBe('001')
            ->and($result[0]->level)->toBe('Regional')
            ->and($result[1]->memberId)->toBe('002')
            ->and($result[1]->level)->toBe('State');
    });

    it('handles judge without certifications', function () {
        $mockClient = new MockClient([
            GetJudgesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [
                        [
                            'MemberID' => '67890',
                            'LastName' => 'Williams',
                            'FirstName' => 'Sarah',
                            'Discipline' => 'WAG',
                            'MemberType' => 'JUDGE',
                            'Status' => 'Active',
                            'Level' => 'Judge',
                        ],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetJudgesRequest(58025))->dto();

        expect($result[0]->certifications)->toBe([]);
    });

    it('returns empty array when no reservations in response', function () {
        $mockClient = new MockClient([
            GetJudgesRequest::class => MockResponse::make([
                'data' => [
                    'reservations' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetJudgesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when reservations key is missing', function () {
        $mockClient = new MockClient([
            GetJudgesRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetJudgesRequest(58025))->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
