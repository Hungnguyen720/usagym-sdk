<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

// Laravel integration tests use Orchestra Testbench TestCase (must be listed before general Feature tests)
pest()->extend(Tests\Feature\Laravel\TestCase::class)->in('Feature/Laravel');

// Base TestCase for general feature tests (excluding Laravel directory which uses its own TestCase)
pest()->extend(Tests\TestCase::class)->in('Feature/Resources');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeValidEnum', function () {
    return $this->toBeInstanceOf(BackedEnum::class);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

/**
 * Get a fixture file path
 */
function fixturePath(string $name): string
{
    return __DIR__ . '/Fixtures/' . $name;
}

/**
 * Load a JSON fixture file
 *
 * @return array<string, mixed>
 */
function loadFixture(string $name): array
{
    $path = fixturePath($name);

    if (!file_exists($path)) {
        throw new RuntimeException("Fixture file not found: {$path}");
    }

    $content = file_get_contents($path);

    if ($content === false) {
        throw new RuntimeException("Failed to read fixture file: {$path}");
    }

    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}
