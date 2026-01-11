<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use AustinW\UsaGym\UsaGym as UsaGymConnector;
use AustinW\UsaGym\Resources\DisciplineResource;
use AustinW\UsaGym\Resources\PersonResource;
use AustinW\UsaGym\Resources\SanctionResource;

/**
 * @method static bool test()
 * @method static DisciplineResource disciplines()
 * @method static PersonResource person()
 * @method static SanctionResource sanctions(int $sanctionId)
 *
 * @see UsaGymConnector
 */
class UsaGym extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return UsaGymConnector::class;
    }
}
