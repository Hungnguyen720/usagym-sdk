<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use AustinW\UsaGym\Data\DisciplineData;

/**
 * Get all active disciplines
 *
 * @see https://api.usagym.org/v4/discipline
 */
class GetDisciplinesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/discipline';
    }

    /**
     * @return array<DisciplineData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $disciplines = $response->json('data.disciplines') ?? [];

        return array_map(
            fn(array $item) => DisciplineData::fromArray($item),
            $disciplines
        );
    }
}
