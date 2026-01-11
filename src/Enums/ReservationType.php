<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums;

enum ReservationType: string
{
    case Athlete = 'athlete';
    case Coach = 'coach';
    case Judge = 'judge';
    case Individual = 'individual';
    case Group = 'group';
    case Club = 'club';

    /**
     * Get the API endpoint path segment
     */
    public function endpoint(): string
    {
        return $this->value;
    }

    /**
     * Check if this type returns individual member data
     */
    public function isIndividualType(): bool
    {
        return in_array($this, [
            self::Athlete,
            self::Coach,
            self::Judge,
            self::Individual,
        ]);
    }
}
