<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests\Reservations;

use BackedEnum;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\AthleteReservation;
use AustinW\UsaGym\Data\CoachReservation;

/**
 * Get all individual reservations for a sanction (athletes and coaches)
 *
 * @see https://api.usagym.org/v4/sanction/{sanctionId}/reservations/individual
 */
class GetIndividualsRequest extends Request
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
        return "/sanction/{$this->sanctionId}/reservations/individual";
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
     * Parse the response - returns both athletes and coaches
     *
     * @return array{athletes: array<AthleteReservation>, coaches: array<CoachReservation>}
     */
    public function createDtoFromResponse(Response $response): array
    {
        $reservations = $response->json('data.reservations') ?? [];

        $athletes = [];
        $coaches = [];

        foreach ($reservations as $item) {
            // Coaches have Level = "Coach"
            if (($item['Level'] ?? '') === 'Coach' || ($item['ReservationType'] ?? '') === 'coach') {
                $coaches[] = CoachReservation::fromArray($item);
            } else {
                $athletes[] = AthleteReservation::fromArray($item);
            }
        }

        return [
            'athletes' => $athletes,
            'coaches' => $coaches,
        ];
    }
}
