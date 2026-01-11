<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Reservations;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\CoachReservation;

/**
 * Get coach reservations for a sanction
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/reservations/coach
 */
class GetCoachesRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $sanctionId
     * @param array<int>|null $clubs Filter by club IDs
     */
    public function __construct(
        protected readonly int $sanctionId,
        protected readonly ?array $clubs = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sanction/{$this->sanctionId}/reservations/coach";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->clubs !== null && count($this->clubs) > 0) {
            $query['clubs'] = implode(',', $this->clubs);
        }

        return $query;
    }

    /**
     * @return array<CoachReservation>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $reservations = $response->json('data.reservations') ?? [];

        return array_map(
            fn(array $item) => CoachReservation::fromArray($item),
            $reservations
        );
    }
}
