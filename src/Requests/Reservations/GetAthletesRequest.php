<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Reservations;

use BackedEnum;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\AthleteReservation;

/**
 * Get athlete reservations for a sanction
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/reservations/athlete
 */
class GetAthletesRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $sanctionId
     * @param array<int>|null $clubs Filter by club IDs
     * @param array<BackedEnum|string>|null $levels Filter by level codes
     */
    public function __construct(
        protected readonly int $sanctionId,
        protected readonly ?array $clubs = null,
        protected readonly ?array $levels = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sanction/{$this->sanctionId}/reservations/athlete";
    }

    protected function defaultQuery(): array
    {
        $query = [];

        if ($this->clubs !== null && count($this->clubs) > 0) {
            $query['clubs'] = implode(',', $this->clubs);
        }

        if ($this->levels !== null && count($this->levels) > 0) {
            $query['levels'] = implode(',', array_map(
                fn($level) => $level instanceof BackedEnum ? $level->value : $level,
                $this->levels
            ));
        }

        return $query;
    }

    /**
     * @return array<AthleteReservation>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $reservations = $response->json('data.reservations') ?? [];

        return array_map(
            fn(array $item) => AthleteReservation::fromArray($item),
            $reservations
        );
    }
}
