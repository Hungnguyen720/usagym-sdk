<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums;

enum MemberStatus: string
{
    case Active = 'Active';
    case Pending = 'Pending';
    case Expired = 'Expired';
    case Banned = 'Banned';
    case Suspended = 'Suspended';
    case Terminated = 'Terminated';
    case Approval = 'Approval';

    /**
     * Check if the member is in good standing
     */
    public function isGoodStanding(): bool
    {
        return $this === self::Active;
    }

    /**
     * Check if the member can potentially participate
     */
    public function canParticipate(): bool
    {
        return in_array($this, [
            self::Active,
            self::Pending,
        ]);
    }

    /**
     * Check if this status indicates a problem
     */
    public function isProblem(): bool
    {
        return in_array($this, [
            self::Banned,
            self::Suspended,
            self::Terminated,
        ]);
    }
}
