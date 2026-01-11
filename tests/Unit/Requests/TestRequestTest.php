<?php

declare(strict_types=1);

use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Enums\Method;

describe('TestRequest', function () {
    it('uses GET method', function () {
        $request = new TestRequest();

        expect($request->getMethod())->toBe(Method::GET);
    });

    it('has correct endpoint', function () {
        $request = new TestRequest();

        expect($request->resolveEndpoint())->toBe('/test');
    });

    it('has no query parameters', function () {
        $request = new TestRequest();

        expect($request->query()->all())->toBe([]);
    });

    it('can be instantiated without arguments', function () {
        $request = new TestRequest();

        expect($request)->toBeInstanceOf(TestRequest::class);
    });
});
