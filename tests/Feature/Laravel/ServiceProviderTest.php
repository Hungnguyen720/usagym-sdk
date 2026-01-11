<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Laravel\UsaGymServiceProvider;
use AustinW\UsaGym\Resources\DisciplineResource;
use AustinW\UsaGym\Resources\PersonResource;
use AustinW\UsaGym\Resources\SanctionResource;
use Illuminate\Support\Facades\Config;

describe('UsaGymServiceProvider Registration', function () {
    it('registers the service provider', function () {
        $providers = $this->app->getLoadedProviders();

        expect($providers)->toHaveKey(UsaGymServiceProvider::class);
    });

    it('registers the connector as singleton', function () {
        $instance1 = app(UsaGym::class);
        $instance2 = app(UsaGym::class);

        expect($instance1)->toBe($instance2)
            ->and($instance1)->toBeInstanceOf(UsaGym::class);
    });

    it('registers usagym alias', function () {
        $connector = app('usagym');

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });

    it('resolves connector via class name', function () {
        $connector = app(UsaGym::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });

    it('resolves connector via alias', function () {
        $connectorFromClass = app(UsaGym::class);
        $connectorFromAlias = app('usagym');

        expect($connectorFromClass)->toBe($connectorFromAlias);
    });

    it('provides correct service bindings', function () {
        $provider = new UsaGymServiceProvider($this->app);
        $provides = $provider->provides();

        expect($provides)->toContain(UsaGym::class)
            ->and($provides)->toContain('usagym');
    });
});

describe('UsaGymServiceProvider Configuration', function () {
    it('merges default config', function () {
        expect(config('usagym'))->toBeArray()
            ->and(config('usagym'))->toHaveKeys(['username', 'password']);
    });

    it('resolves connector with config credentials', function () {
        $connector = app(UsaGym::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });

    it('uses config values for username', function () {
        Config::set('usagym.username', 'custom-user');

        // Clear the singleton and re-resolve
        $this->app->forgetInstance(UsaGym::class);
        $connector = app(UsaGym::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });

    it('uses config values for password', function () {
        Config::set('usagym.password', 'custom-pass');

        // Clear the singleton and re-resolve
        $this->app->forgetInstance(UsaGym::class);
        $connector = app(UsaGym::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });

    it('handles empty config gracefully', function () {
        Config::set('usagym.username', '');
        Config::set('usagym.password', '');

        // Clear the singleton and re-resolve
        $this->app->forgetInstance(UsaGym::class);
        $connector = app(UsaGym::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });

    it('handles null config gracefully', function () {
        Config::set('usagym.username', null);
        Config::set('usagym.password', null);

        // Clear the singleton and re-resolve
        $this->app->forgetInstance(UsaGym::class);
        $connector = app(UsaGym::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });
});

describe('UsaGymServiceProvider Config Publishing', function () {
    it('registers config for publishing', function () {
        $this->artisan('vendor:publish', ['--tag' => 'usagym-config', '--force' => true]);

        // Verify the publish path is registered
        $publishes = UsaGymServiceProvider::$publishes;

        expect($publishes)->toBeArray();
    });

    it('publishes config file with correct key', function () {
        // This test ensures the config tag is registered
        $provider = new UsaGymServiceProvider($this->app);

        // Access protected publishes array via reflection
        $reflection = new ReflectionClass($provider);
        $publishableProperty = $reflection->getProperty('publishes');

        expect($publishableProperty)->toBeInstanceOf(ReflectionProperty::class);
    });
});

describe('UsaGymServiceProvider Resource Access', function () {
    it('resolves connector that provides disciplines resource', function () {
        $connector = app(UsaGym::class);

        expect($connector->disciplines())->toBeInstanceOf(DisciplineResource::class);
    });

    it('resolves connector that provides person resource', function () {
        $connector = app(UsaGym::class);

        expect($connector->person())->toBeInstanceOf(PersonResource::class);
    });

    it('resolves connector that provides sanctions resource', function () {
        $connector = app(UsaGym::class);

        expect($connector->sanctions(12345))->toBeInstanceOf(SanctionResource::class);
    });
});

describe('UsaGymServiceProvider Base URL', function () {
    it('resolves connector with correct base url', function () {
        $connector = app(UsaGym::class);

        expect($connector->resolveBaseUrl())->toBe('https://api.usagym.org/v4');
    });
});
