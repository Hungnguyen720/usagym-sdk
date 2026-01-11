<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Reservations;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\ClubReservation;

/**
 * Get club reservations for a sanction
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/reservations/club
 */
class GetClubsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $sanctionId
     * @param array<int>|null $clubs Filter by specific club IDs
     */
    public function __construct(
        protected readonly int $sanctionId,
        protected readonly ?array $clubs = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sanction/{$this->sanctionId}/reservations/club";
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
     * @return array<ClubReservation>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $reservations = $response->json('data.reservations') ?? [];

        return array_map(
            fn(array $item) => ClubReservation::fromArray($item),
            $reservations
        );
    }
}
