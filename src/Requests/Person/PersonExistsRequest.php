<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Person;

use DateTimeInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * Verify a person exists with the provided member ID, date of birth, and last name
 *
 * @see https://api.usagym.org/v4/person/exists
 */
class PersonExistsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $memberId,
        protected readonly string $lastName,
        protected readonly string|DateTimeInterface $dateOfBirth,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/person/exists';
    }

    protected function defaultQuery(): array
    {
        $dob = $this->dateOfBirth instanceof DateTimeInterface
            ? $this->dateOfBirth->format('Y-m-d')
            : $this->dateOfBirth;

        return [
            'm' => $this->memberId,
            'lname' => $this->lastName,
            'dob' => $dob,
        ];
    }

    public function createDtoFromResponse(Response $response): bool
    {
        return (bool) ($response->json('data.valid') ?? false);
    }
}
