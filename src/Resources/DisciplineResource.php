<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Resources;

use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Data\DisciplineData;
use AustinW\UsaGym\Requests\GetDisciplinesRequest;

/**
 * Resource for discipline-related operations
 */
class DisciplineResource
{
    public function __construct(
        protected readonly UsaGym $connector,
    ) {}

    /**
     * Get all active disciplines
     *
     * @return array<DisciplineData>
     */
    public function all(): array
    {
        $response = $this->connector->send(new GetDisciplinesRequest());

        return $response->dtoOrFail();
    }
}
