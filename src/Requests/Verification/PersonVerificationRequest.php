<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Verification;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\VerificationResult;
use AustinW\UsaGym\Enums\ReservationType;

/**
 * Verify person(s) for a sanction
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/verification/{memberType}
 */
class PersonVerificationRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $sanctionId
     * @param string $memberType One of: athlete, coach, judge
     * @param array<string|int> $memberIds Member IDs to verify
     */
    public function __construct(
        protected readonly int $sanctionId,
        protected readonly string $memberType,
        protected readonly array $memberIds,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sanction/{$this->sanctionId}/verification/{$this->memberType}";
    }

    protected function defaultQuery(): array
    {
        return [
            'people' => implode(',', $this->memberIds),
        ];
    }

    /**
     * @return array<VerificationResult>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $members = $response->json('data.members') ?? [];

        return array_map(
            fn(array $item) => VerificationResult::fromArray($item),
            $members
        );
    }
}
