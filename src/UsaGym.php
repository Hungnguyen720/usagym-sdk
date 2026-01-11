<?php

declare(strict_types=1);

namespace AustinW\UsaGym;

use Saloon\Http\Connector;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Http\Response;
use AustinW\UsaGym\Resources\DisciplineResource;
use AustinW\UsaGym\Resources\PersonResource;
use AustinW\UsaGym\Resources\SanctionResource;
use AustinW\UsaGym\Requests\TestRequest;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\Exceptions\AuthenticationException;

class UsaGym extends Connector
{
    use AcceptsJson;

    public function __construct(
        protected readonly string $username,
        protected readonly string $password,
    ) {}

    public function resolveBaseUrl(): string
    {
        return 'https://api.usagym.org/v4';
    }

    protected function defaultAuth(): BasicAuthenticator
    {
        return new BasicAuthenticator($this->username, $this->password);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Test API credentials
     *
     * @throws UsaGymException
     */
    public function test(): bool
    {
        $response = $this->send(new TestRequest());

        if ($response->status() === 401 || $response->status() === 403) {
            throw new AuthenticationException(
                $response->json('message') ?? 'Authorization Error: Forbidden',
                $response
            );
        }

        return $response->successful() && $response->json('status') === 'success';
    }

    /**
     * Access discipline endpoints
     */
    public function disciplines(): DisciplineResource
    {
        return new DisciplineResource($this);
    }

    /**
     * Access person endpoints
     */
    public function person(): PersonResource
    {
        return new PersonResource($this);
    }

    /**
     * Access sanction-specific endpoints
     */
    public function sanctions(int $sanctionId): SanctionResource
    {
        return new SanctionResource($this, $sanctionId);
    }

    /**
     * Get the configured timeout
     */
    public function getTimeout(): int
    {
        return $this->config()->get('timeout', 30);
    }

    /**
     * Set a custom timeout
     */
    public function setTimeout(int $seconds): static
    {
        $this->config()->add('timeout', $seconds);

        return $this;
    }
}
