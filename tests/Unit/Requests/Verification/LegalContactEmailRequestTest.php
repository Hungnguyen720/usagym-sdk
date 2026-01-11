<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\Verification\LegalContactEmailRequest;
use AustinW\UsaGym\UsaGym;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('LegalContactEmailRequest', function () {
    it('uses GET method', function () {
        $request = new LegalContactEmailRequest('person', '12345', 'parent@example.com');

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint for person verification', function () {
        $request = new LegalContactEmailRequest('person', '12345', 'parent@example.com');

        expect($request->resolveEndpoint())->toBe('/person/12345/verification/legalContact/email/parent@example.com');
    });

    it('has correct endpoint for group verification', function () {
        $request = new LegalContactEmailRequest('group', '67890', 'legal@example.com');

        expect($request->resolveEndpoint())->toBe('/group/67890/verification/legalContact/email/legal@example.com');
    });

    it('handles integer refTypeId', function () {
        $request = new LegalContactEmailRequest('person', 12345, 'test@example.com');

        expect($request->resolveEndpoint())->toBe('/person/12345/verification/legalContact/email/test@example.com');
    });

    it('handles string refTypeId', function () {
        $request = new LegalContactEmailRequest('person', 'ABC123', 'test@example.com');

        expect($request->resolveEndpoint())->toBe('/person/ABC123/verification/legalContact/email/test@example.com');
    });

    it('includes email address in endpoint path', function () {
        $request = new LegalContactEmailRequest('person', '12345', 'john.doe+test@subdomain.example.com');

        expect($request->resolveEndpoint())->toBe('/person/12345/verification/legalContact/email/john.doe+test@subdomain.example.com');
    });

    it('has no query parameters', function () {
        $request = new LegalContactEmailRequest('person', '12345', 'parent@example.com');

        expect($request->query()->all())->toBe([]);
    });
});

describe('LegalContactEmailRequest::createDtoFromResponse', function () {
    it('returns true when email is valid', function () {
        $mockClient = new MockClient([
            LegalContactEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => true,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new LegalContactEmailRequest('person', '12345', 'parent@example.com'))->dto();

        expect($result)->toBeTrue();
    });

    it('returns false when email is not valid', function () {
        $mockClient = new MockClient([
            LegalContactEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => false,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new LegalContactEmailRequest('person', '12345', 'wrong@example.com'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns false when valid key is missing', function () {
        $mockClient = new MockClient([
            LegalContactEmailRequest::class => MockResponse::make([
                'data' => [],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new LegalContactEmailRequest('person', '12345', 'parent@example.com'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns true for truthy values (1)', function () {
        $mockClient = new MockClient([
            LegalContactEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 1,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new LegalContactEmailRequest('person', '12345', 'parent@example.com'))->dto();

        expect($result)->toBeTrue();
    });

    it('returns false for falsy values (0)', function () {
        $mockClient = new MockClient([
            LegalContactEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 0,
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new LegalContactEmailRequest('person', '12345', 'parent@example.com'))->dto();

        expect($result)->toBeFalse();
    });

    it('returns true for string true value', function () {
        $mockClient = new MockClient([
            LegalContactEmailRequest::class => MockResponse::make([
                'data' => [
                    'valid' => 'true',
                ],
            ]),
        ]);

        $connector = new UsaGym('test-user', 'test-pass');
        $connector->withMockClient($mockClient);

        $result = $connector->send(new LegalContactEmailRequest('group', '67890', 'contact@example.com'))->dto();

        expect($result)->toBeTrue();
    });
});

describe('LegalContactEmailRequest with different refTypes', function () {
    it('works with person refType', function () {
        $request = new LegalContactEmailRequest('person', '12345', 'test@example.com');

        expect($request->resolveEndpoint())->toStartWith('/person/');
    });

    it('works with group refType', function () {
        $request = new LegalContactEmailRequest('group', '67890', 'test@example.com');

        expect($request->resolveEndpoint())->toStartWith('/group/');
    });
});
