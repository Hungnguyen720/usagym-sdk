<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Enums\Levels;

enum GfaLevel: string
{
    // Power Team Gym Levels
    case PowerTeamGym1 = 'GGTLEVEL01';
    case PowerTeamGym2 = 'GGTLEVEL02';
    case PowerTeamGym3 = 'GGTLEVEL03';
    case PowerTeamGym4 = 'GGTLEVEL04';
    case PowerTeamGym5 = 'GGTLEVEL05';
    case PowerTeamGym6 = 'GGTLEVEL06';
    case PowerTeamGym7 = 'GGTLEVEL07';
    case PowerTeamGym8 = 'GGTLEVEL08';
    case PowerTeamGym9 = 'GGTLEVEL09';
    case PowerTeamGym10 = 'GGTLEVEL10';
    case PowerTeamGymHugs1 = 'GGTHLEVEL01';
    case PowerTeamGymHugs2 = 'GGTHLEVEL02';
    case PowerTeamGymHugs1Unified = 'GGTHLEVEL01U';
    case PowerTeamGymHugs2Unified = 'GGTHLEVEL02U';

    // Team Acrobatics & Tumbling Levels
    case TeamAcroLevel1 = 'GGATLEVEL01';
    case TeamAcroLevel2 = 'GGATLEVEL02';
    case TeamAcroLevel3 = 'GGATLEVEL03';
    case TeamAcroLevel4 = 'GGATLEVEL04';

    // HUGS - Women's Levels
    case HugsWomenSapphire = 'GGHWSAPPHIRE';
    case HugsWomenRuby = 'GGHWRUBY';
    case HugsWomenEmerald = 'GGHWEMERALD';

    // HUGS - Men's Levels
    case HugsMenHero = 'GGHMHERO';
    case HugsMenSuperHero = 'GGHMSUPERHERO';

    // HUGS - Rhythmic Levels
    case HugsRhythmicBronze = 'GGHRBRONZE';
    case HugsRhythmicSilver = 'GGHRSILVER';
    case HugsRhythmicGold = 'GGHRGOLD';
    case HugsRhythmicPlatinum = 'GGHRPLATINUM';

    // HUGS - T&T Levels
    case HugsTrampolineLevel1 = 'GGHTLEVEL01';
    case HugsTrampolineLevel2 = 'GGHTLEVEL02';
    case HugsTrampolineLevel2Plus = 'GGHTLEVEL02PLUS';

    /**
     * Get the display value returned by the API
     */
    public function displayValue(): string
    {
        return match ($this) {
            self::PowerTeamGym1 => '1',
            self::PowerTeamGym2 => '2',
            self::PowerTeamGym3 => '3',
            self::PowerTeamGym4 => '4',
            self::PowerTeamGym5 => '5',
            self::PowerTeamGym6 => '6',
            self::PowerTeamGym7 => '7',
            self::PowerTeamGym8 => '8',
            self::PowerTeamGym9 => '9',
            self::PowerTeamGym10 => '10',
            self::PowerTeamGymHugs1 => 'HUGS1',
            self::PowerTeamGymHugs2 => 'HUGS2',
            self::PowerTeamGymHugs1Unified => 'HUGS1U',
            self::PowerTeamGymHugs2Unified => 'HUGS2U',
            self::TeamAcroLevel1 => '1',
            self::TeamAcroLevel2 => '2',
            self::TeamAcroLevel3 => '3',
            self::TeamAcroLevel4 => '4',
            self::HugsWomenSapphire => 'Sapphire',
            self::HugsWomenRuby => 'Ruby',
            self::HugsWomenEmerald => 'Emerald',
            self::HugsMenHero => 'Hero',
            self::HugsMenSuperHero => 'Super Hero',
            self::HugsRhythmicBronze => 'Bronze',
            self::HugsRhythmicSilver => 'Silver',
            self::HugsRhythmicGold => 'Gold',
            self::HugsRhythmicPlatinum => 'Platinum',
            self::HugsTrampolineLevel1 => 'Level 1',
            self::HugsTrampolineLevel2 => 'Level 2',
            self::HugsTrampolineLevel2Plus => 'Level 2 Plus',
        };
    }

    /**
     * Check if this is a Power Team Gym level
     */
    public function isPowerTeamGym(): bool
    {
        return str_starts_with($this->value, 'GGT');
    }

    /**
     * Check if this is a Team Acrobatics & Tumbling level
     */
    public function isTeamAcro(): bool
    {
        return str_starts_with($this->value, 'GGAT');
    }

    /**
     * Check if this is a HUGS level
     */
    public function isHugs(): bool
    {
        return str_starts_with($this->value, 'GGH') || str_contains($this->value, 'HUGS');
    }

    /**
     * Get the HUGS discipline if applicable
     */
    public function hugsCategory(): ?string
    {
        return match (true) {
            str_starts_with($this->value, 'GGHW') => 'Women',
            str_starts_with($this->value, 'GGHM') => 'Men',
            str_starts_with($this->value, 'GGHR') => 'Rhythmic',
            str_starts_with($this->value, 'GGHT') => 'T&T',
            str_contains($this->value, 'HUGS') => 'Power Team Gym',
            default => null,
        };
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
