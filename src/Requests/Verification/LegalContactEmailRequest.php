<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Verification;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * Verify legal contact email address for a person or group
 *
 * @see https://api.usagym.org/v4/{refType}/{refTypeId}/verification/legalContact/email/{emailAddress}
 */
class LegalContactEmailRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param string $refType Either "person" or "group"
     * @param string|int $refTypeId PersonID or GroupID
     * @param string $emailAddress Email address to verify
     */
    public function __construct(
        protected readonly string $refType,
        protected readonly string|int $refTypeId,
        protected readonly string $emailAddress,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/{$this->refType}/{$this->refTypeId}/verification/legalContact/email/{$this->emailAddress}";
    }

    public function createDtoFromResponse(Response $response): bool
    {
        return (bool) ($response->json('data.valid') ?? false);
    }
}
