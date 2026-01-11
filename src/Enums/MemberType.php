<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums;

enum MemberType: string
{
    case Athlete = 'ATHL';
    case CompetitiveCoach = 'CCOACH';
    case Judge = 'JUDGE';

    // Deprecated types (kept for backward compatibility with historical data)
    case Instructor = 'INST';           // Deprecated 21/22 season
    case JuniorProfessional = 'JPRO';   // Deprecated 21/22 season
    case Professional = 'PRO';          // Deprecated 21/22 season
    case InternationalCoach = 'IFCO';   // Deprecated 21/22 season
    case InternationalJudge = 'IFJO';   // Deprecated 21/22 season
    case InternationalAthlete = 'IFAT'; // Deprecated 21/22 season
    case InternationalTrainee = 'INTR'; // Deprecated 20/21 season

    /**
     * Get the display name
     */
    public function label(): string
    {
        return match ($this) {
            self::Athlete => 'Athlete',
            self::CompetitiveCoach => 'Competitive Coach',
            self::Judge => 'Judge',
            self::Instructor => 'Instructor (Deprecated)',
            self::JuniorProfessional => 'Junior Professional (Deprecated)',
            self::Professional => 'Professional (Deprecated)',
            self::InternationalCoach => 'International Coach (Deprecated)',
            self::InternationalJudge => 'International Judge (Deprecated)',
            self::InternationalAthlete => 'International Athlete (Deprecated)',
            self::InternationalTrainee => 'International Trainee (Deprecated)',
        };
    }

    /**
     * Check if this is a deprecated member type
     */
    public function isDeprecated(): bool
    {
        return in_array($this, [
            self::Instructor,
            self::JuniorProfessional,
            self::Professional,
            self::InternationalCoach,
            self::InternationalJudge,
            self::InternationalAthlete,
            self::InternationalTrainee,
        ]);
    }

    /**
     * Check if this member type represents an athlete
     */
    public function isAthlete(): bool
    {
        return in_array($this, [
            self::Athlete,
            self::InternationalAthlete,
            self::InternationalTrainee,
        ]);
    }

    /**
     * Check if this member type represents a coach
     */
    public function isCoach(): bool
    {
        return in_array($this, [
            self::CompetitiveCoach,
            self::Instructor,
            self::InternationalCoach,
        ]);
    }

    /**
     * Check if this member type represents a judge
     */
    public function isJudge(): bool
    {
        return in_array($this, [
            self::Judge,
            self::Professional,
            self::JuniorProfessional,
            self::InternationalJudge,
        ]);
    }
}
