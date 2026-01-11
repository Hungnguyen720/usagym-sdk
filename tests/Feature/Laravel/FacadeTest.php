<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Laravel\Facades\UsaGym as UsaGymFacade;
use AustinW\UsaGym\Resources\DisciplineResource;
use AustinW\UsaGym\Resources\PersonResource;
use AustinW\UsaGym\Resources\SanctionResource;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('UsaGym Facade Resolution', function () {
    it('resolves to connector instance', function () {
        expect(UsaGymFacade::getFacadeRoot())->toBeInstanceOf(UsaGym::class);
    });

    it('returns same instance as container', function () {
        $facadeInstance = UsaGymFacade::getFacadeRoot();
        $containerInstance = app(UsaGym::class);

        expect($facadeInstance)->toBe($containerInstance);
    });

    it('facade accessor returns correct class name', function () {
        // Use reflection to access the protected method
        $reflection = new ReflectionClass(UsaGymFacade::class);
        $method = $reflection->getMethod('getFacadeAccessor');

        expect($method->invoke(null))->toBe(UsaGym::class);
    });
});

describe('UsaGym Facade Methods', function () {
    it('provides access to disciplines resource via facade', function () {
        expect(UsaGymFacade::disciplines())->toBeInstanceOf(DisciplineResource::class);
    });

    it('provides access to person resource via facade', function () {
        expect(UsaGymFacade::person())->toBeInstanceOf(PersonResource::class);
    });

    it('provides access to sanctions resource via facade', function () {
        expect(UsaGymFacade::sanctions(12345))->toBeInstanceOf(SanctionResource::class);
    });

    it('passes sanction id correctly through facade', function () {
        $sanctionResource = UsaGymFacade::sanctions(67890);

        expect($sanctionResource->getSanctionId())->toBe(67890);
    });
});

describe('UsaGym Facade Alias Registration', function () {
    it('facade alias is registered as class alias', function () {
        // The package alias 'UsaGym' maps to the facade class
        // This is registered via getPackageAliases in the TestCase
        expect(class_exists(\UsaGym::class))->toBeTrue();
    });

    it('facade alias points to facade class', function () {
        // The class alias 'UsaGym' should point to the Facade class
        // We verify this by checking the parent class of the alias
        expect(get_parent_class(\UsaGym::class))->toBe(\Illuminate\Support\Facades\Facade::class);
    });

    it('facade accessor returns connector class string', function () {
        // The facade should use the UsaGym connector class as its accessor
        $reflection = new ReflectionClass(UsaGymFacade::class);
        $method = $reflection->getMethod('getFacadeAccessor');

        expect($method->invoke(null))->toBe(\AustinW\UsaGym\UsaGym::class);
    });
});

describe('UsaGym Facade with Mock Client', function () {
    it('can use mock client via facade', function () {
        $mockClient = new MockClient([
            MockResponse::make(['status' => 'success'], 200),
        ]);

        UsaGymFacade::withMockClient($mockClient);

        expect(UsaGymFacade::getFacadeRoot())->toBeInstanceOf(UsaGym::class);
    });
});

describe('UsaGym Facade Base URL', function () {
    it('resolves base url correctly via facade', function () {
        expect(UsaGymFacade::resolveBaseUrl())->toBe('https://api.usagym.org/v4');
    });
});
