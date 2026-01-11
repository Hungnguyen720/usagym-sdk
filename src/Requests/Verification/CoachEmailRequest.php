<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Verification;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * Verify coach email address for a person/group at a sanction
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/{refType}/{refTypeId}/verification/coach/email/{emailAddress}
 */
class CoachEmailRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $sanctionId
     * @param string $refType Either "person" or "group"
     * @param string|int $refTypeId PersonID or GroupID
     * @param string $emailAddress Email address to verify
     */
    public function __construct(
        protected readonly int $sanctionId,
        protected readonly string $refType,
        protected readonly string|int $refTypeId,
        protected readonly string $emailAddress,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sanction/{$this->sanctionId}/{$this->refType}/{$this->refTypeId}/verification/coach/email/{$this->emailAddress}";
    }

    public function createDtoFromResponse(Response $response): bool
    {
        return (bool) ($response->json('data.valid') ?? false);
    }
}
