<?php

declare(strict_types=1);

namespace Tests;

use AustinW\UsaGym\UsaGym;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

abstract class TestCase extends BaseTestCase
{
    protected UsaGym $connector;

    protected MockClient $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connector = new UsaGym('test-user', 'test-pass');
        $this->mockClient = new MockClient();
    }

    /**
     * Create a mock connector with predefined responses
     *
     * @param array<class-string, MockResponse>|array<MockResponse> $responses
     */
    protected function mockConnector(array $responses = []): UsaGym
    {
        $mockClient = new MockClient($responses);
        $this->connector->withMockClient($mockClient);

        return $this->connector;
    }

    /**
     * Create a successful JSON mock response
     *
     * @param array<string, mixed> $data
     */
    protected function mockJsonResponse(array $data, int $status = 200): MockResponse
    {
        return MockResponse::make($data, $status);
    }

    /**
     * Create an error mock response
     */
    protected function mockErrorResponse(string $message, int $status = 400): MockResponse
    {
        return MockResponse::make(['message' => $message], $status);
    }

    /**
     * Load fixture data and create a mock response
     */
    protected function mockFixtureResponse(string $fixtureName, int $status = 200): MockResponse
    {
        return MockResponse::make(loadFixture($fixtureName), $status);
    }
}
