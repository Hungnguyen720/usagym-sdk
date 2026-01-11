<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums\Levels;

enum WomensArtisticLevel: string
{
    // Xcel Program
    case Bronze = 'WBRONZE';
    case Silver = 'WSILVER';
    case Gold = 'WGOLD';
    case Platinum = 'WPLATINUM';
    case Diamond = 'WDIAMOND';
    case Sapphire = 'WSAPPHIRE';

    // JO Program
    case Level1 = 'WLEVEL01';
    case Level2 = 'WLEVEL02';
    case Level3 = 'WLEVEL03';
    case Level4 = 'WLEVEL04';
    case Level5 = 'WLEVEL05';
    case Level6 = 'WLEVEL06';
    case Level7 = 'WLEVEL07';
    case Level8 = 'WLEVEL08';
    case Level9 = 'WLEVEL09';
    case Level10 = 'WLEVEL10';

    // Special Levels
    case Open = 'WOPEN';
    case Elite = 'WELITE';
    case Tops = 'WTOPS';
    case Exhibition = 'WEXHIB';
    case Hopes = 'WHOPES';
    case Hugs = 'WHUGS';

    /**
     * Get the display value returned by the API
     */
    public function displayValue(): string
    {
        return match ($this) {
            self::Bronze => 'Bronze',
            self::Silver => 'Silver',
            self::Gold => 'Gold',
            self::Platinum => 'Platinum',
            self::Diamond => 'Diamond',
            self::Sapphire => 'Sapphire',
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
            self::Open => 'Open',
            self::Elite => 'Elite',
            self::Tops => 'TOPS',
            self::Exhibition => 'Exhib Camp',
            self::Hopes => 'Hopes',
            self::Hugs => 'HUGS',
        };
    }

    /**
     * Check if this is a compulsory level
     */
    public function isCompulsory(): bool
    {
        return in_array($this, [
            self::Level1,
            self::Level2,
            self::Level3,
            self::Level4,
            self::Level5,
        ]);
    }

    /**
     * Check if this is an optional level
     */
    public function isOptional(): bool
    {
        return in_array($this, [
            self::Level6,
            self::Level7,
            self::Level8,
            self::Level9,
            self::Level10,
        ]);
    }

    /**
     * Check if this is an Xcel level
     */
    public function isXcel(): bool
    {
        return in_array($this, [
            self::Bronze,
            self::Silver,
            self::Gold,
            self::Platinum,
            self::Diamond,
            self::Sapphire,
        ]);
    }

    /**
     * Check if this is an elite/advanced level
     */
    public function isElite(): bool
    {
        return in_array($this, [
            self::Elite,
            self::Hopes,
            self::Tops,
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
