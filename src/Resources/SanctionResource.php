<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Resources;

use AustinW\UsaGym\UsaGym;

/**
 * Resource for sanction-specific operations
 */
class SanctionResource
{
    public function __construct(
        protected readonly UsaGym $connector,
        protected readonly int $sanctionId,
    ) {}

    /**
     * Get the sanction ID
     */
    public function getSanctionId(): int
    {
        return $this->sanctionId;
    }

    /**
     * Access reservation endpoints
     */
    public function reservations(): ReservationResource
    {
        return new ReservationResource($this->connector, $this->sanctionId);
    }

    /**
     * Access verification endpoints
     */
    public function verification(): VerificationResource
    {
        return new VerificationResource($this->connector, $this->sanctionId);
    }
}
