<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;

describe('UsaGym Config Structure', function () {
    it('has username key', function () {
        expect(config('usagym.username'))->not->toBeNull();
    });

    it('has password key', function () {
        expect(config('usagym.password'))->not->toBeNull();
    });

    it('returns array for usagym config', function () {
        expect(config('usagym'))->toBeArray();
    });

    it('has exactly two keys', function () {
        $config = config('usagym');

        expect($config)->toHaveCount(2);
    });
});

describe('UsaGym Config Values', function () {
    it('can get username from config', function () {
        Config::set('usagym.username', 'my-username');

        expect(config('usagym.username'))->toBe('my-username');
    });

    it('can get password from config', function () {
        Config::set('usagym.password', 'my-password');

        expect(config('usagym.password'))->toBe('my-password');
    });

    it('can set config values at runtime', function () {
        Config::set('usagym.username', 'runtime-user');
        Config::set('usagym.password', 'runtime-pass');

        expect(config('usagym.username'))->toBe('runtime-user')
            ->and(config('usagym.password'))->toBe('runtime-pass');
    });
});

describe('UsaGym Config Defaults', function () {
    it('uses test values from defineEnvironment', function () {
        // Values set in TestCase::defineEnvironment
        expect(config('usagym.username'))->toBe('test-user')
            ->and(config('usagym.password'))->toBe('test-pass');
    });
});

describe('UsaGym Config Integration', function () {
    it('config values are used when resolving connector', function () {
        Config::set('usagym.username', 'integration-user');
        Config::set('usagym.password', 'integration-pass');

        // Clear singleton and re-resolve
        $this->app->forgetInstance(\AustinW\UsaGym\UsaGym::class);

        $connector = app(\AustinW\UsaGym\UsaGym::class);

        expect($connector)->toBeInstanceOf(\AustinW\UsaGym\UsaGym::class);
    });
});

describe('UsaGym Config File Path', function () {
    it('config file exists at expected location', function () {
        $configPath = __DIR__ . '/../../../src/Laravel/config/usagym.php';

        expect(file_exists($configPath))->toBeTrue();
    });

    it('config file returns array', function () {
        $configPath = __DIR__ . '/../../../src/Laravel/config/usagym.php';
        $config = require $configPath;

        expect($config)->toBeArray();
    });

    it('config file has correct keys', function () {
        $configPath = __DIR__ . '/../../../src/Laravel/config/usagym.php';
        $config = require $configPath;

        expect($config)->toHaveKeys(['username', 'password']);
    });
});
