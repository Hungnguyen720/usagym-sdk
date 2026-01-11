<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\GetDisciplinesRequest;
use AustinW\UsaGym\Data\DisciplineData;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('GetDisciplinesRequest', function () {
    it('uses GET method', function () {
        $request = new GetDisciplinesRequest();

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint', function () {
        $request = new GetDisciplinesRequest();

        expect($request->resolveEndpoint())->toBe('/discipline');
    });

    it('has no query parameters', function () {
        $request = new GetDisciplinesRequest();

        expect($request->query()->all())->toBe([]);
    });

    it('can be instantiated without arguments', function () {
        $request = new GetDisciplinesRequest();

        expect($request)->toBeInstanceOf(GetDisciplinesRequest::class);
    });
});

describe('GetDisciplinesRequest::createDtoFromResponse', function () {
    it('creates array of DisciplineData from response', function () {
        $mockClient = new MockClient([
            GetDisciplinesRequest::class => MockResponse::make([
                'data' => [
                    'disciplines' => [
                        ['Code' => 'WAG', 'Name' => 'Women', 'FullName' => "Women's Artistic"],
                        ['Code' => 'MAG', 'Name' => 'Men', 'FullName' => "Men's Artistic"],
                        ['Code' => 'RG', 'Name' => 'Rhythmic', 'FullName' => 'Rhythmic'],
                    ],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetDisciplinesRequest())->dto();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(3)
            ->and($result[0])->toBeInstanceOf(DisciplineData::class)
            ->and($result[0]->code)->toBe('WAG')
            ->and($result[0]->name)->toBe('Women')
            ->and($result[0]->fullName)->toBe("Women's Artistic")
            ->and($result[1]->code)->toBe('MAG')
            ->and($result[2]->code)->toBe('RG');
    });

    it('returns empty array when no disciplines in response', function () {
        $mockClient = new MockClient([
            GetDisciplinesRequest::class => MockResponse::make([
                'data' => [
                    'disciplines' => [],
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetDisciplinesRequest())->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });

    it('returns empty array when disciplines key is missing', function () {
        $mockClient = new MockClient([
            GetDisciplinesRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new GetDisciplinesRequest())->dto();

        expect($result)->toBeArray()
            ->and($result)->toBeEmpty();
    });
});
