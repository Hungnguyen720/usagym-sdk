<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Person\PersonExistsRequest;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('PersonExistsRequest', function () {
    it('uses GET method', function () {
        $request = new PersonExistsRequest('12345', 'Smith', '1990-05-15');

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint', function () {
        $request = new PersonExistsRequest('12345', 'Smith', '1990-05-15');

        expect($request->resolveEndpoint())->toBe('/person/exists');
    });
});

describe('PersonExistsRequest query parameters', function () {
    it('builds query with member ID, last name, and date of birth string', function () {
        $request = new PersonExistsRequest('12345', 'Smith', '1990-05-15');

        $query = $request->query()->all();

        expect($query)->toHaveKey('m')
            ->and($query['m'])->toBe('12345')
            ->and($query)->toHaveKey('lname')
            ->and($query['lname'])->toBe('Smith')
            ->and($query)->toHaveKey('dob')
            ->and($query['dob'])->toBe('1990-05-15');
    });

    it('builds query with DateTimeInterface for date of birth', function () {
        $dateOfBirth = new DateTimeImmutable('1995-12-25');
        $request = new PersonExistsRequest('67890', 'Jones', $dateOfBirth);

        $query = $request->query()->all();

        expect($query['dob'])->toBe('1995-12-25');
    });

    it('formats DateTime correctly', function () {
        $dateOfBirth = new DateTime('2000-01-01');
        $request = new PersonExistsRequest('11111', 'Doe', $dateOfBirth);

        $query = $request->query()->all();

        expect($query['dob'])->toBe('2000-01-01');
    });

    it('handles different member ID formats', function () {
        $request = new PersonExistsRequest('ABC123', 'TestLastName', '2005-06-15');

        $query = $request->query()->all();

        expect($query['m'])->toBe('ABC123')
            ->and($query['lname'])->toBe('TestLastName');
    });
});

describe('PersonExistsRequest::createDtoFromResponse', function () {
    it('returns true when person exists (valid is true)', function () {
        $mockClient = new MockClient([
            PersonExistsRequest::class => MockResponse::make([
                'data' => [
                    'valid' => true,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonExistsRequest('12345', 'Smith', '1990-05-15'))->dto();

        expect($result)->toBeTrue();
    });

    it('returns false when person does not exist (valid is false)', function () {
        $mockClient = new MockClient([
            PersonExistsRequest::class => MockResponse::make([
                'data' => [
                    'valid' => false,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonExistsRequest('12345', 'Smith', '1990-05-15'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns false when valid key is missing', function () {
        $mockClient = new MockClient([
            PersonExistsRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonExistsRequest('12345', 'Smith', '1990-05-15'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns true for truthy values (1)', function () {
        $mockClient = new MockClient([
            PersonExistsRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 1,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonExistsRequest('12345', 'Smith', '1990-05-15'))->dto();

        expect($result)->toBeTrue();
    });

    it('returns false for falsy values (0)', function () {
        $mockClient = new MockClient([
            PersonExistsRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 0,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new PersonExistsRequest('12345', 'Smith', '1990-05-15'))->dto();

        expect($result)->toBeFalse();
    });
});
