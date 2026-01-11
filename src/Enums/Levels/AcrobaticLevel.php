<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums\Levels;

enum AcrobaticLevel: string
{
    // Individual/Pair/Group Levels
    case Level1 = 'SLEVEL01';
    case Level2 = 'SLEVEL02';
    case Level3 = 'SLEVEL03';
    case Level4 = 'SLEVEL04';
    case Level5 = 'SLEVEL05';
    case Level6 = 'SLEVEL06';
    case Level7 = 'SLEVEL07';
    case Level8 = 'SLEVEL08';
    case Level9 = 'SLEVEL09';
    case Level10 = 'SLEVEL10';
    case Elite = 'SELITE';
    case Age11To16 = 'S11-16';
    case JuniorElite12To18 = 'SJELITE12-18';
    case JuniorElite13To19 = 'SJELITE13-19';
    case SeniorElite = 'SSELITE';
    case Exhibition = 'SAEXHIB';

    // Block Levels (for individuals only)
    case BlockBronze = 'SGBRONZE';
    case BlockSilver = 'SGSILVER';
    case BlockGold = 'SGGOLD';
    case BlockPlatinum = 'SGPLATINUM';
    case BlockDiamond = 'SGDIAMOND';

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
            self::Elite => 'Elite',
            self::Age11To16 => '11-16',
            self::JuniorElite12To18 => 'JElite12-18',
            self::JuniorElite13To19 => 'JElite13-19',
            self::SeniorElite => 'SElite',
            self::Exhibition => 'Exhib',
            self::BlockBronze => 'Bronze',
            self::BlockSilver => 'Silver',
            self::BlockGold => 'Gold',
            self::BlockPlatinum => 'Platinum',
            self::BlockDiamond => 'Diamond',
        };
    }

    /**
     * Check if this is a block level (individuals only)
     */
    public function isBlockLevel(): bool
    {
        return in_array($this, [
            self::BlockBronze,
            self::BlockSilver,
            self::BlockGold,
            self::BlockPlatinum,
            self::BlockDiamond,
        ]);
    }

    /**
     * Check if this is a pair/group level
     */
    public function isPairGroupLevel(): bool
    {
        return !$this->isBlockLevel();
    }

    /**
     * Check if this is an elite level
     */
    public function isElite(): bool
    {
        return in_array($this, [
            self::Elite,
            self::JuniorElite12To18,
            self::JuniorElite13To19,
            self::SeniorElite,
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
