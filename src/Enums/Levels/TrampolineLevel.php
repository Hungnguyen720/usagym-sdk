<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums\Levels;

enum TrampolineLevel: string
{
    // Standard Levels (apply to all apparatus)
    case Level1 = 'TLEVEL01';
    case Level2 = 'TLEVEL02';
    case Level3 = 'TLEVEL03';
    case Level4 = 'TLEVEL04';
    case Level5 = 'TLEVEL05';
    case Level6 = 'TLEVEL06';
    case Level7 = 'TLEVEL07';
    case Level8 = 'TLEVEL08';
    case Level9 = 'TLEVEL09';
    case Level10 = 'TLEVEL10';

    // Elite Levels
    case OpenElite = 'TOELITE';
    case YouthElite11To12 = 'TYELITE1112';
    case YouthElite13To14 = 'TYELITE1314';
    case JuniorElite = 'TJELITE';
    case IntermediateElite = 'TIELITE';
    case SeniorElite = 'TSELITE';

    // Special Levels
    case Exhibition = 'TEXHIB';

    // HUGS Levels
    case Hugs1 = 'TTHUGS1';
    case Hugs2 = 'TTHUGS2';
    case Hugs3 = 'TTHUGS3';
    case Hugs4 = 'TTHUGS4';

    /**
     * Get the display value returned by the API
     */
    public function displayValue(): string
    {
        return match ($this) {
            self::Level1 => '1',
            self::Level2 => '2',
            self::Level3 => '3',
            self::Level4 => '4',
            self::Level5 => '5',
            self::Level6 => '6',
            self::Level7 => '7',
            self::Level8 => '8',
            self::Level9 => '9',
            self::Level10 => '10',
            self::OpenElite => 'OElite',
            self::YouthElite11To12 => 'YElite1112',
            self::YouthElite13To14 => 'YElite1314',
            self::JuniorElite => 'JElite',
            self::IntermediateElite => 'IElite',
            self::SeniorElite => 'SElite',
            self::Exhibition => 'Exhib',
            self::Hugs1 => 'HUGS1',
            self::Hugs2 => 'HUGS2',
            self::Hugs3 => 'HUGS3',
            self::Hugs4 => 'HUGS4',
        };
    }

    /**
     * Check if this is an elite level
     */
    public function isElite(): bool
    {
        return in_array($this, [
            self::OpenElite,
            self::YouthElite11To12,
            self::YouthElite13To14,
            self::JuniorElite,
            self::IntermediateElite,
            self::SeniorElite,
        ]);
    }

    /**
     * Check if this is a HUGS level
     */
    public function isHugs(): bool
    {
        return in_array($this, [
            self::Hugs1,
            self::Hugs2,
            self::Hugs3,
            self::Hugs4,
        ]);
    }

    /**
     * Create from API return value
     */
    public static function fromDisplayValue(string $value): ?self
    {
        foreach (self::cases() as $case) {
            if (strcasecmp($case->displayValue(), $value) === 0) {
                return $case;
            }
        }
        return null;
    }
}
