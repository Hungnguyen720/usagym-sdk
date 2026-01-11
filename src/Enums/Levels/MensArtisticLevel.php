<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums\Levels;

enum MensArtisticLevel: string
{
    // Xcel Program
    case Bronze = 'MBRONZE';
    case Silver = 'MSILVER';
    case Gold = 'MGOLD';
    case Platinum = 'MPLATINUM';

    // JO Program
    case Level1 = 'MLEVEL01';
    case Level2 = 'MLEVEL02';
    case Level3 = 'MLEVEL03';

    // Development Program D1
    case Level3D1 = 'M3D1';
    case Level4D1 = 'M4D1';
    case Level5D1 = 'M5D1';

    // Development Program D2
    case Level3D2 = 'M3D2';
    case Level4D2 = 'M4D2';
    case Level5D2 = 'M5D2';

    // JN (Junior National) Program
    case Level6JN = 'MLEVEL06JN';
    case Level7JN = 'MLEVEL07JN';
    case Level8JN = 'MLEVEL08JN';
    case Level9JN = 'MLEVEL09JN';
    case Level10JNJunior = 'MLEVEL10JNJR';
    case Level10JNSenior = 'MLEVEL10JNSR';

    // JE (Junior Elite) Program
    case Level6JE = 'MLEVEL06JE';
    case Level8JE = 'MLEVEL08JE';
    case Level9JE = 'MLEVEL09JE';
    case Level10JEJunior = 'MLEVEL10JEJR';
    case Level10JESenior = 'MLEVEL10JESR';

    // Special Levels
    case Elite = 'MELITE';
    case Exhibition = 'MEXHIB';
    case Hugs = 'MHUGS';

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
            self::Level1 => '1',
            self::Level2 => '2',
            self::Level3 => '3',
            self::Level3D1 => '3D1',
            self::Level4D1 => '4D1',
            self::Level5D1 => '5D1',
            self::Level3D2 => '3D2',
            self::Level4D2 => '4D2',
            self::Level5D2 => '5D2',
            self::Level6JN, self::Level6JE => '6',
            self::Level7JN => '7',
            self::Level8JN, self::Level8JE => '8',
            self::Level9JN, self::Level9JE => '9',
            self::Level10JNJunior, self::Level10JNSenior,
            self::Level10JEJunior, self::Level10JESenior => '10',
            self::Elite => 'Elite',
            self::Exhibition => 'Exhib',
            self::Hugs => 'HUGS',
        };
    }

    /**
     * Check if this is a development program level
     */
    public function isDevelopment(): bool
    {
        return in_array($this, [
            self::Level3D1,
            self::Level4D1,
            self::Level5D1,
            self::Level3D2,
            self::Level4D2,
            self::Level5D2,
        ]);
    }

    /**
     * Check if this is a Junior National level
     */
    public function isJuniorNational(): bool
    {
        return in_array($this, [
            self::Level6JN,
            self::Level7JN,
            self::Level8JN,
            self::Level9JN,
            self::Level10JNJunior,
            self::Level10JNSenior,
        ]);
    }

    /**
     * Check if this is a Junior Elite level
     */
    public function isJuniorElite(): bool
    {
        return in_array($this, [
            self::Level6JE,
            self::Level8JE,
            self::Level9JE,
            self::Level10JEJunior,
            self::Level10JESenior,
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
