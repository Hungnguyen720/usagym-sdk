<?php

declare(strict_types=1);

use AustinW\UsaGym\UsaGym;
use Illuminate\Support\Facades\Config;

describe('UsaGym Dependency Injection', function () {
    it('can inject connector into closure', function () {
        $result = app()->call(function (UsaGym $connector) {
            return $connector;
        });

        expect($result)->toBeInstanceOf(UsaGym::class);
    });

    it('can inject connector into class method', function () {
        $testClass = new class {
            public ?UsaGym $connector = null;

            public function setConnector(UsaGym $connector): void
            {
                $this->connector = $connector;
            }
        };

        app()->call([$testClass, 'setConnector']);

        expect($testClass->connector)->toBeInstanceOf(UsaGym::class);
    });

    it('injects same singleton instance', function () {
        $instances = [];

        app()->call(function (UsaGym $connector) use (&$instances) {
            $instances[] = $connector;
        });

        app()->call(function (UsaGym $connector) use (&$instances) {
            $instances[] = $connector;
        });

        expect($instances[0])->toBe($instances[1]);
    });
});

describe('UsaGym Constructor Injection', function () {
    it('can be injected via constructor', function () {
        $testClass = new class(app(UsaGym::class)) {
            public function __construct(
                public readonly UsaGym $connector
            ) {}
        };

        expect($testClass->connector)->toBeInstanceOf(UsaGym::class);
    });

    it('resolves correctly when building class with dependencies', function () {
        $testClass = app()->make(TestServiceWithUsaGym::class);

        expect($testClass->connector)->toBeInstanceOf(UsaGym::class);
    });
});

describe('UsaGym Service Container Binding', function () {
    it('is bound as singleton', function () {
        $instance1 = app(UsaGym::class);
        $instance2 = app(UsaGym::class);

        expect(spl_object_id($instance1))->toBe(spl_object_id($instance2));
    });

    it('can be rebound with new instance', function () {
        $original = app(UsaGym::class);

        $this->app->forgetInstance(UsaGym::class);
        Config::set('usagym.username', 'new-user');

        $new = app(UsaGym::class);

        expect($original)->not->toBe($new)
            ->and($new)->toBeInstanceOf(UsaGym::class);
    });

    it('alias points to same binding', function () {
        $fromClass = app(UsaGym::class);
        $fromAlias = app('usagym');

        expect($fromClass)->toBe($fromAlias);
    });
});

describe('UsaGym Interface Binding', function () {
    it('can bind interface to implementation', function () {
        $this->app->bind(TestConnectorInterface::class, function ($app) {
            return $app->make(UsaGym::class);
        });

        $connector = app(TestConnectorInterface::class);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });
});

describe('UsaGym Contextual Binding', function () {
    it('supports contextual binding', function () {
        $this->app->when(TestContextualService::class)
            ->needs(UsaGym::class)
            ->give(function ($app) {
                return new UsaGym('contextual-user', 'contextual-pass');
            });

        $service = app(TestContextualService::class);

        expect($service->connector)->toBeInstanceOf(UsaGym::class);
    });
});

describe('UsaGym Container Make', function () {
    it('can make connector with custom parameters', function () {
        $connector = app()->makeWith(UsaGym::class, [
            'username' => 'custom-user',
            'password' => 'custom-pass',
        ]);

        expect($connector)->toBeInstanceOf(UsaGym::class);
    });
});

// Test helper classes

class TestServiceWithUsaGym
{
    public function __construct(
        public readonly UsaGym $connector
    ) {}
}

interface TestConnectorInterface
{
}

class TestContextualService
{
    public function __construct(
        public readonly UsaGym $connector
    ) {}
}
