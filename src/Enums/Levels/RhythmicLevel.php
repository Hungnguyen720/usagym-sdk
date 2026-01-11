<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums\Levels;

enum RhythmicLevel: string
{
    // Individual Levels
    case SuperStars = 'RSS';
    case Copper = 'RCOPPER';
    case Bronze = 'RBRONZE';
    case Silver = 'RSILVER';
    case Gold = 'RGOLD';
    case Diamond = 'RDIAMOND';
    case Platinum = 'RPLATINUM';
    case Level1 = 'RLEVEL01';
    case Level2 = 'RLEVEL02';
    case Level3 = 'RLEVEL03';
    case Level4 = 'RLEVEL04';
    case Level5 = 'RLEVEL05';
    case Level6 = 'RLEVEL06';
    case Level7 = 'RLEVEL07';
    case Level8 = 'RLEVEL08';
    case Level9 = 'RLEVEL09';
    case Level10 = 'RLEVEL10';
    case Elite = 'RELITE';
    case Exhibition = 'REXHIBI';
    case Hugs = 'RHUGS';

    // Group Levels
    case GroupLevel4 = 'RGLEVEL04';
    case GroupLevel5 = 'RGLEVEL05';
    case GroupLevel6 = 'RGLEVEL06';
    case GroupBeginner = 'RGBEGINNER';
    case GroupIntermediate = 'RGINTERMED';
    case GroupAdvanced = 'RGADVANCED';
    case FigJunior = 'RFIGJR';
    case FigSenior = 'RFIGSR';

    /**
     * Get the display value returned by the API
     */
    public function displayValue(): string
    {
        return match ($this) {
            self::SuperStars => 'SuperStars',
            self::Copper => 'Copper',
            self::Bronze => 'Bronze',
            self::Silver => 'Silver',
            self::Gold => 'Gold',
            self::Diamond => 'Diamond',
            self::Platinum => 'Platinum',
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
            self::Elite => 'Elite',
            self::Exhibition => 'Exhib',
            self::Hugs => 'HUGS',
            self::GroupLevel4 => '4',
            self::GroupLevel5 => '5',
            self::GroupLevel6 => '6',
            self::GroupBeginner => 'Beginner',
            self::GroupIntermediate => 'Intermediate',
            self::GroupAdvanced => 'Advanced',
            self::FigJunior => 'FIG Jr.',
            self::FigSenior => 'FIG Sr.',
        };
    }

    /**
     * Check if this is a group level
     */
    public function isGroupLevel(): bool
    {
        return in_array($this, [
            self::GroupLevel4,
            self::GroupLevel5,
            self::GroupLevel6,
            self::GroupBeginner,
            self::GroupIntermediate,
            self::GroupAdvanced,
            self::FigJunior,
            self::FigSenior,
        ]);
    }

    /**
     * Check if this is an individual level
     */
    public function isIndividualLevel(): bool
    {
        return !$this->isGroupLevel();
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
