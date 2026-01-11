<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Verification\CoachEmailRequest;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('CoachEmailRequest', function () {
    it('uses GET method', function () {
        $request = new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com');

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint for person verification', function () {
        $request = new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com');

        expect($request->resolveEndpoint())->toBe('/sanction/58025/person/12345/verification/coach/email/coach@example.com');
    });

    it('has correct endpoint for group verification', function () {
        $request = new CoachEmailRequest(58025, 'group', '67890', 'coach@example.com');

        expect($request->resolveEndpoint())->toBe('/sanction/58025/group/67890/verification/coach/email/coach@example.com');
    });

    it('includes sanction ID in endpoint path', function () {
        $request = new CoachEmailRequest(99999, 'person', '12345', 'coach@example.com');

        expect($request->resolveEndpoint())->toBe('/sanction/99999/person/12345/verification/coach/email/coach@example.com');
    });

    it('handles integer refTypeId', function () {
        $request = new CoachEmailRequest(58025, 'person', 12345, 'test@example.com');

        expect($request->resolveEndpoint())->toBe('/sanction/58025/person/12345/verification/coach/email/test@example.com');
    });

    it('handles string refTypeId', function () {
        $request = new CoachEmailRequest(58025, 'person', 'ABC123', 'test@example.com');

        expect($request->resolveEndpoint())->toBe('/sanction/58025/person/ABC123/verification/coach/email/test@example.com');
    });

    it('includes email address in endpoint path', function () {
        $request = new CoachEmailRequest(58025, 'person', '12345', 'john.doe+coach@subdomain.example.com');

        expect($request->resolveEndpoint())->toBe('/sanction/58025/person/12345/verification/coach/email/john.doe+coach@subdomain.example.com');
    });

    it('has no query parameters', function () {
        $request = new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com');

        expect($request->query()->all())->toBe([]);
    });
});

describe('CoachEmailRequest::createDtoFromResponse', function () {
    it('returns true when email is valid', function () {
        $mockClient = new MockClient([
            CoachEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => true,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com'))->dto();

        expect($result)->toBeTrue();
    });

    it('returns false when email is not valid', function () {
        $mockClient = new MockClient([
            CoachEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => false,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new CoachEmailRequest(58025, 'person', '12345', 'wrong@example.com'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns false when valid key is missing', function () {
        $mockClient = new MockClient([
            CoachEmailRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns true for truthy values (1)', function () {
        $mockClient = new MockClient([
            CoachEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 1,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com'))->dto();

        expect($result)->toBeTrue();
    });

    it('returns false for falsy values (0)', function () {
        $mockClient = new MockClient([
            CoachEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 0,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new CoachEmailRequest(58025, 'person', '12345', 'coach@example.com'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns true for string true value', function () {
        $mockClient = new MockClient([
            CoachEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 'true',
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new CoachEmailRequest(58025, 'group', '67890', 'coach@example.com'))->dto();

        expect($result)->toBeTrue();
    });
});

describe('CoachEmailRequest with different refTypes', function () {
    it('works with person refType', function () {
        $request = new CoachEmailRequest(58025, 'person', '12345', 'test@example.com');

        expect($request->resolveEndpoint())->toContain('/person/');
    });

    it('works with group refType', function () {
        $request = new CoachEmailRequest(58025, 'group', '67890', 'test@example.com');

        expect($request->resolveEndpoint())->toContain('/group/');
    });
});

describe('CoachEmailRequest with various sanction IDs', function () {
    it('handles small sanction ID', function () {
        $request = new CoachEmailRequest(1, 'person', '12345', 'coach@example.com');

        expect($request->resolveEndpoint())->toStartWith('/sanction/1/');
    });

    it('handles large sanction ID', function () {
        $request = new CoachEmailRequest(999999, 'person', '12345', 'coach@example.com');

        expect($request->resolveEndpoint())->toStartWith('/sanction/999999/');
    });
});
