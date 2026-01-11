<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Reservations;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\JudgeReservation;

/**
 * Get judge reservations for a sanction
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/reservations/judge
 */
class GetJudgesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $sanctionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sanction/{$this->sanctionId}/reservations/judge";
    }

    /**
     * @return array<JudgeReservation>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $reservations = $response->json('data.reservations') ?? [];

        return array_map(
            fn(array $item) => JudgeReservation::fromArray($item),
            $reservations
        );
    }
}
