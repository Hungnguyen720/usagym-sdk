<?php

declare(strict_types=1);

namespace Tests\Feature\Laravel;

use AustinW\UsaGym\Laravel\UsaGymServiceProvider;
use AustinW\UsaGym\UsaGym;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [UsaGymServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'UsaGym' => \AustinW\UsaGym\Laravel\Facades\UsaGym::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('usagym.username', 'test-user');
        $app['config']->set('usagym.password', 'test-pass');
    }

    /**
     * Get a fresh application instance with clean bindings.
     */
    protected function getCleanApp(): \Illuminate\Foundation\Application
    {
        return $this->app;
    }

    /**
     * Create a mock client with predefined responses.
     *
     * @param array<class-string, MockResponse>|array<MockResponse> $responses
     */
    protected function mockConnector(array $responses = []): UsaGym
    {
        $mockClient = new MockClient($responses);
        $connector = app(UsaGym::class);
        $connector->withMockClient($mockClient);

        return $connector;
    }

    /**
     * Create a successful JSON mock response.
     *
     * @param array<string, mixed> $data
     */
    protected function mockJsonResponse(array $data, int $status = 200): MockResponse
    {
        return MockResponse::make($data, $status);
    }

    /**
     * Create an error mock response.
     */
    protected function mockErrorResponse(string $message, int $status = 400): MockResponse
    {
        return MockResponse::make(['message' => $message], $status);
    }
}
