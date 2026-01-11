<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Levels\GfaLevel;

describe('GfaLevel', function () {
    describe('enum cases', function () {
        it('has exactly 30 cases', function () {
            expect(GfaLevel::cases())->toHaveCount(30);
        });

        describe('Power Team Gym levels', function () {
            it('has PowerTeamGym1 through PowerTeamGym10 with correct values', function () {
                expect(GfaLevel::PowerTeamGym1->value)->toBe('GGTLEVEL01');
                expect(GfaLevel::PowerTeamGym2->value)->toBe('GGTLEVEL02');
                expect(GfaLevel::PowerTeamGym3->value)->toBe('GGTLEVEL03');
                expect(GfaLevel::PowerTeamGym4->value)->toBe('GGTLEVEL04');
                expect(GfaLevel::PowerTeamGym5->value)->toBe('GGTLEVEL05');
                expect(GfaLevel::PowerTeamGym6->value)->toBe('GGTLEVEL06');
                expect(GfaLevel::PowerTeamGym7->value)->toBe('GGTLEVEL07');
                expect(GfaLevel::PowerTeamGym8->value)->toBe('GGTLEVEL08');
                expect(GfaLevel::PowerTeamGym9->value)->toBe('GGTLEVEL09');
                expect(GfaLevel::PowerTeamGym10->value)->toBe('GGTLEVEL10');
            });

            it('has PowerTeamGymHugs1 case with correct value', function () {
                expect(GfaLevel::PowerTeamGymHugs1->value)->toBe('GGTHLEVEL01');
            });

            it('has PowerTeamGymHugs2 case with correct value', function () {
                expect(GfaLevel::PowerTeamGymHugs2->value)->toBe('GGTHLEVEL02');
            });

            it('has PowerTeamGymHugs1Unified case with correct value', function () {
                expect(GfaLevel::PowerTeamGymHugs1Unified->value)->toBe('GGTHLEVEL01U');
            });

            it('has PowerTeamGymHugs2Unified case with correct value', function () {
                expect(GfaLevel::PowerTeamGymHugs2Unified->value)->toBe('GGTHLEVEL02U');
            });
        });

        describe('Team Acrobatics & Tumbling levels', function () {
            it('has TeamAcroLevel1 through TeamAcroLevel4 with correct values', function () {
                expect(GfaLevel::TeamAcroLevel1->value)->toBe('GGATLEVEL01');
                expect(GfaLevel::TeamAcroLevel2->value)->toBe('GGATLEVEL02');
                expect(GfaLevel::TeamAcroLevel3->value)->toBe('GGATLEVEL03');
                expect(GfaLevel::TeamAcroLevel4->value)->toBe('GGATLEVEL04');
            });
        });

        describe('HUGS Women levels', function () {
            it('has HugsWomenSapphire case with correct value', function () {
                expect(GfaLevel::HugsWomenSapphire->value)->toBe('GGHWSAPPHIRE');
            });

            it('has HugsWomenRuby case with correct value', function () {
                expect(GfaLevel::HugsWomenRuby->value)->toBe('GGHWRUBY');
            });

            it('has HugsWomenEmerald case with correct value', function () {
                expect(GfaLevel::HugsWomenEmerald->value)->toBe('GGHWEMERALD');
            });
        });

        describe('HUGS Men levels', function () {
            it('has HugsMenHero case with correct value', function () {
                expect(GfaLevel::HugsMenHero->value)->toBe('GGHMHERO');
            });

            it('has HugsMenSuperHero case with correct value', function () {
                expect(GfaLevel::HugsMenSuperHero->value)->toBe('GGHMSUPERHERO');
            });
        });

        describe('HUGS Rhythmic levels', function () {
            it('has HugsRhythmicBronze case with correct value', function () {
                expect(GfaLevel::HugsRhythmicBronze->value)->toBe('GGHRBRONZE');
            });

            it('has HugsRhythmicSilver case with correct value', function () {
                expect(GfaLevel::HugsRhythmicSilver->value)->toBe('GGHRSILVER');
            });

            it('has HugsRhythmicGold case with correct value', function () {
                expect(GfaLevel::HugsRhythmicGold->value)->toBe('GGHRGOLD');
            });

            it('has HugsRhythmicPlatinum case with correct value', function () {
                expect(GfaLevel::HugsRhythmicPlatinum->value)->toBe('GGHRPLATINUM');
            });
        });

        describe('HUGS T&T levels', function () {
            it('has HugsTrampolineLevel1 case with correct value', function () {
                expect(GfaLevel::HugsTrampolineLevel1->value)->toBe('GGHTLEVEL01');
            });

            it('has HugsTrampolineLevel2 case with correct value', function () {
                expect(GfaLevel::HugsTrampolineLevel2->value)->toBe('GGHTLEVEL02');
            });

            it('has HugsTrampolineLevel2Plus case with correct value', function () {
                expect(GfaLevel::HugsTrampolineLevel2Plus->value)->toBe('GGHTLEVEL02PLUS');
            });
        });
    });

    describe('displayValue()', function () {
        describe('Power Team Gym display values', function () {
            it('returns numeric values for Power Team Gym levels', function () {
                expect(GfaLevel::PowerTeamGym1->displayValue())->toBe('1');
                expect(GfaLevel::PowerTeamGym5->displayValue())->toBe('5');
                expect(GfaLevel::PowerTeamGym10->displayValue())->toBe('10');
            });

            it('returns HUGS display values for Power Team Gym HUGS levels', function () {
                expect(GfaLevel::PowerTeamGymHugs1->displayValue())->toBe('HUGS1');
                expect(GfaLevel::PowerTeamGymHugs2->displayValue())->toBe('HUGS2');
                expect(GfaLevel::PowerTeamGymHugs1Unified->displayValue())->toBe('HUGS1U');
                expect(GfaLevel::PowerTeamGymHugs2Unified->displayValue())->toBe('HUGS2U');
            });
        });

        describe('Team Acrobatics display values', function () {
            it('returns numeric values for Team Acro levels', function () {
                expect(GfaLevel::TeamAcroLevel1->displayValue())->toBe('1');
                expect(GfaLevel::TeamAcroLevel2->displayValue())->toBe('2');
                expect(GfaLevel::TeamAcroLevel3->displayValue())->toBe('3');
                expect(GfaLevel::TeamAcroLevel4->displayValue())->toBe('4');
            });
        });

        describe('HUGS Women display values', function () {
            it('returns "Sapphire" for HugsWomenSapphire', function () {
                expect(GfaLevel::HugsWomenSapphire->displayValue())->toBe('Sapphire');
            });

            it('returns "Ruby" for HugsWomenRuby', function () {
                expect(GfaLevel::HugsWomenRuby->displayValue())->toBe('Ruby');
            });

            it('returns "Emerald" for HugsWomenEmerald', function () {
                expect(GfaLevel::HugsWomenEmerald->displayValue())->toBe('Emerald');
            });
        });

        describe('HUGS Men display values', function () {
            it('returns "Hero" for HugsMenHero', function () {
                expect(GfaLevel::HugsMenHero->displayValue())->toBe('Hero');
            });

            it('returns "Super Hero" for HugsMenSuperHero', function () {
                expect(GfaLevel::HugsMenSuperHero->displayValue())->toBe('Super Hero');
            });
        });

        describe('HUGS Rhythmic display values', function () {
            it('returns "Bronze" for HugsRhythmicBronze', function () {
                expect(GfaLevel::HugsRhythmicBronze->displayValue())->toBe('Bronze');
            });

            it('returns "Silver" for HugsRhythmicSilver', function () {
                expect(GfaLevel::HugsRhythmicSilver->displayValue())->toBe('Silver');
            });

            it('returns "Gold" for HugsRhythmicGold', function () {
                expect(GfaLevel::HugsRhythmicGold->displayValue())->toBe('Gold');
            });

            it('returns "Platinum" for HugsRhythmicPlatinum', function () {
                expect(GfaLevel::HugsRhythmicPlatinum->displayValue())->toBe('Platinum');
            });
        });

        describe('HUGS T&T display values', function () {
            it('returns "Level 1" for HugsTrampolineLevel1', function () {
                expect(GfaLevel::HugsTrampolineLevel1->displayValue())->toBe('Level 1');
            });

            it('returns "Level 2" for HugsTrampolineLevel2', function () {
                expect(GfaLevel::HugsTrampolineLevel2->displayValue())->toBe('Level 2');
            });

            it('returns "Level 2 Plus" for HugsTrampolineLevel2Plus', function () {
                expect(GfaLevel::HugsTrampolineLevel2Plus->displayValue())->toBe('Level 2 Plus');
            });
        });
    });

    describe('isPowerTeamGym()', function () {
        it('returns true for PowerTeamGym numeric levels', function () {
            expect(GfaLevel::PowerTeamGym1->isPowerTeamGym())->toBeTrue();
            expect(GfaLevel::PowerTeamGym5->isPowerTeamGym())->toBeTrue();
            expect(GfaLevel::PowerTeamGym10->isPowerTeamGym())->toBeTrue();
        });

        it('returns true for PowerTeamGym HUGS levels', function () {
            expect(GfaLevel::PowerTeamGymHugs1->isPowerTeamGym())->toBeTrue();
            expect(GfaLevel::PowerTeamGymHugs2->isPowerTeamGym())->toBeTrue();
            expect(GfaLevel::PowerTeamGymHugs1Unified->isPowerTeamGym())->toBeTrue();
            expect(GfaLevel::PowerTeamGymHugs2Unified->isPowerTeamGym())->toBeTrue();
        });

        it('returns false for Team Acro levels', function () {
            expect(GfaLevel::TeamAcroLevel1->isPowerTeamGym())->toBeFalse();
            expect(GfaLevel::TeamAcroLevel4->isPowerTeamGym())->toBeFalse();
        });

        it('returns false for HUGS discipline levels', function () {
            expect(GfaLevel::HugsWomenSapphire->isPowerTeamGym())->toBeFalse();
            expect(GfaLevel::HugsMenHero->isPowerTeamGym())->toBeFalse();
            expect(GfaLevel::HugsRhythmicBronze->isPowerTeamGym())->toBeFalse();
            expect(GfaLevel::HugsTrampolineLevel1->isPowerTeamGym())->toBeFalse();
        });
    });

    describe('isTeamAcro()', function () {
        it('returns true for Team Acro levels', function () {
            expect(GfaLevel::TeamAcroLevel1->isTeamAcro())->toBeTrue();
            expect(GfaLevel::TeamAcroLevel2->isTeamAcro())->toBeTrue();
            expect(GfaLevel::TeamAcroLevel3->isTeamAcro())->toBeTrue();
            expect(GfaLevel::TeamAcroLevel4->isTeamAcro())->toBeTrue();
        });

        it('returns false for Power Team Gym levels', function () {
            expect(GfaLevel::PowerTeamGym1->isTeamAcro())->toBeFalse();
            expect(GfaLevel::PowerTeamGymHugs1->isTeamAcro())->toBeFalse();
        });

        it('returns false for HUGS discipline levels', function () {
            expect(GfaLevel::HugsWomenSapphire->isTeamAcro())->toBeFalse();
            expect(GfaLevel::HugsMenHero->isTeamAcro())->toBeFalse();
        });
    });

    describe('isHugs()', function () {
        it('returns true for HUGS Women levels', function () {
            expect(GfaLevel::HugsWomenSapphire->isHugs())->toBeTrue();
            expect(GfaLevel::HugsWomenRuby->isHugs())->toBeTrue();
            expect(GfaLevel::HugsWomenEmerald->isHugs())->toBeTrue();
        });

        it('returns true for HUGS Men levels', function () {
            expect(GfaLevel::HugsMenHero->isHugs())->toBeTrue();
            expect(GfaLevel::HugsMenSuperHero->isHugs())->toBeTrue();
        });

        it('returns true for HUGS Rhythmic levels', function () {
            expect(GfaLevel::HugsRhythmicBronze->isHugs())->toBeTrue();
            expect(GfaLevel::HugsRhythmicSilver->isHugs())->toBeTrue();
            expect(GfaLevel::HugsRhythmicGold->isHugs())->toBeTrue();
            expect(GfaLevel::HugsRhythmicPlatinum->isHugs())->toBeTrue();
        });

        it('returns true for HUGS T&T levels', function () {
            expect(GfaLevel::HugsTrampolineLevel1->isHugs())->toBeTrue();
            expect(GfaLevel::HugsTrampolineLevel2->isHugs())->toBeTrue();
            expect(GfaLevel::HugsTrampolineLevel2Plus->isHugs())->toBeTrue();
        });

        it('returns false for Power Team Gym HUGS levels (they start with GGTH not GGH)', function () {
            // Note: isHugs() checks for str_starts_with('GGH') or str_contains('HUGS')
            // Power Team Gym HUGS values start with 'GGTH' not 'GGH' and don't contain 'HUGS' in value
            expect(GfaLevel::PowerTeamGymHugs1->isHugs())->toBeFalse();
            expect(GfaLevel::PowerTeamGymHugs2->isHugs())->toBeFalse();
            expect(GfaLevel::PowerTeamGymHugs1Unified->isHugs())->toBeFalse();
            expect(GfaLevel::PowerTeamGymHugs2Unified->isHugs())->toBeFalse();
        });

        it('returns false for non-HUGS Power Team Gym levels', function () {
            expect(GfaLevel::PowerTeamGym1->isHugs())->toBeFalse();
            expect(GfaLevel::PowerTeamGym10->isHugs())->toBeFalse();
        });

        it('returns false for Team Acro levels', function () {
            expect(GfaLevel::TeamAcroLevel1->isHugs())->toBeFalse();
            expect(GfaLevel::TeamAcroLevel4->isHugs())->toBeFalse();
        });
    });

    describe('hugsCategory()', function () {
        it('returns "Women" for HUGS Women levels', function () {
            expect(GfaLevel::HugsWomenSapphire->hugsCategory())->toBe('Women');
            expect(GfaLevel::HugsWomenRuby->hugsCategory())->toBe('Women');
            expect(GfaLevel::HugsWomenEmerald->hugsCategory())->toBe('Women');
        });

        it('returns "Men" for HUGS Men levels', function () {
            expect(GfaLevel::HugsMenHero->hugsCategory())->toBe('Men');
            expect(GfaLevel::HugsMenSuperHero->hugsCategory())->toBe('Men');
        });

        it('returns "Rhythmic" for HUGS Rhythmic levels', function () {
            expect(GfaLevel::HugsRhythmicBronze->hugsCategory())->toBe('Rhythmic');
            expect(GfaLevel::HugsRhythmicSilver->hugsCategory())->toBe('Rhythmic');
            expect(GfaLevel::HugsRhythmicGold->hugsCategory())->toBe('Rhythmic');
            expect(GfaLevel::HugsRhythmicPlatinum->hugsCategory())->toBe('Rhythmic');
        });

        it('returns "T&T" for HUGS T&T levels', function () {
            expect(GfaLevel::HugsTrampolineLevel1->hugsCategory())->toBe('T&T');
            expect(GfaLevel::HugsTrampolineLevel2->hugsCategory())->toBe('T&T');
            expect(GfaLevel::HugsTrampolineLevel2Plus->hugsCategory())->toBe('T&T');
        });

        it('returns null for Power Team Gym HUGS levels (they start with GGTH not match hugsCategory patterns)', function () {
            // Note: hugsCategory() checks for specific prefixes
            // Power Team Gym HUGS values start with 'GGTH' which matches T&T pattern (GGHT)
            // But wait - GGTH != GGHT, so they return null
            expect(GfaLevel::PowerTeamGymHugs1->hugsCategory())->toBeNull();
            expect(GfaLevel::PowerTeamGymHugs2->hugsCategory())->toBeNull();
            expect(GfaLevel::PowerTeamGymHugs1Unified->hugsCategory())->toBeNull();
            expect(GfaLevel::PowerTeamGymHugs2Unified->hugsCategory())->toBeNull();
        });

        it('returns null for non-HUGS Power Team Gym levels', function () {
            expect(GfaLevel::PowerTeamGym1->hugsCategory())->toBeNull();
            expect(GfaLevel::PowerTeamGym10->hugsCategory())->toBeNull();
        });

        it('returns null for Team Acro levels', function () {
            expect(GfaLevel::TeamAcroLevel1->hugsCategory())->toBeNull();
            expect(GfaLevel::TeamAcroLevel4->hugsCategory())->toBeNull();
        });
    });

    describe('fromDisplayValue()', function () {
        it('returns correct enum for Power Team Gym numeric levels', function () {
            // Note: fromDisplayValue returns first match, so '1' matches PowerTeamGym1
            $result = GfaLevel::fromDisplayValue('1');
            expect($result)->not->toBeNull();
            expect($result->displayValue())->toBe('1');
        });

        it('returns correct enum for HUGS Women levels', function () {
            expect(GfaLevel::fromDisplayValue('Sapphire'))->toBe(GfaLevel::HugsWomenSapphire);
            expect(GfaLevel::fromDisplayValue('Ruby'))->toBe(GfaLevel::HugsWomenRuby);
            expect(GfaLevel::fromDisplayValue('Emerald'))->toBe(GfaLevel::HugsWomenEmerald);
        });

        it('returns correct enum for HUGS Men levels', function () {
            expect(GfaLevel::fromDisplayValue('Hero'))->toBe(GfaLevel::HugsMenHero);
            expect(GfaLevel::fromDisplayValue('Super Hero'))->toBe(GfaLevel::HugsMenSuperHero);
        });

        it('returns correct enum for HUGS Rhythmic levels', function () {
            expect(GfaLevel::fromDisplayValue('Bronze'))->toBe(GfaLevel::HugsRhythmicBronze);
            expect(GfaLevel::fromDisplayValue('Silver'))->toBe(GfaLevel::HugsRhythmicSilver);
            expect(GfaLevel::fromDisplayValue('Gold'))->toBe(GfaLevel::HugsRhythmicGold);
            expect(GfaLevel::fromDisplayValue('Platinum'))->toBe(GfaLevel::HugsRhythmicPlatinum);
        });

        it('returns correct enum for HUGS T&T levels', function () {
            expect(GfaLevel::fromDisplayValue('Level 1'))->toBe(GfaLevel::HugsTrampolineLevel1);
            expect(GfaLevel::fromDisplayValue('Level 2'))->toBe(GfaLevel::HugsTrampolineLevel2);
            expect(GfaLevel::fromDisplayValue('Level 2 Plus'))->toBe(GfaLevel::HugsTrampolineLevel2Plus);
        });

        it('returns correct enum for case-insensitive match', function () {
            expect(GfaLevel::fromDisplayValue('sapphire'))->toBe(GfaLevel::HugsWomenSapphire);
            expect(GfaLevel::fromDisplayValue('SAPPHIRE'))->toBe(GfaLevel::HugsWomenSapphire);
            expect(GfaLevel::fromDisplayValue('hero'))->toBe(GfaLevel::HugsMenHero);
        });

        it('returns null for non-existent value', function () {
            expect(GfaLevel::fromDisplayValue('NonExistent'))->toBeNull();
        });

        it('returns null for empty string', function () {
            expect(GfaLevel::fromDisplayValue(''))->toBeNull();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(GfaLevel::tryFrom('GGTLEVEL01'))->toBe(GfaLevel::PowerTeamGym1);
            expect(GfaLevel::tryFrom('GGATLEVEL01'))->toBe(GfaLevel::TeamAcroLevel1);
            expect(GfaLevel::tryFrom('GGHWSAPPHIRE'))->toBe(GfaLevel::HugsWomenSapphire);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(GfaLevel::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(GfaLevel::from('GGHMHERO'))->toBe(GfaLevel::HugsMenHero);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => GfaLevel::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('level categorization consistency', function () {
        it('counts exactly 14 Power Team Gym levels', function () {
            $ptgLevels = array_filter(
                GfaLevel::cases(),
                fn (GfaLevel $level) => $level->isPowerTeamGym()
            );
            expect($ptgLevels)->toHaveCount(14);
        });

        it('counts exactly 4 Team Acro levels', function () {
            $teamAcroLevels = array_filter(
                GfaLevel::cases(),
                fn (GfaLevel $level) => $level->isTeamAcro()
            );
            expect($teamAcroLevels)->toHaveCount(4);
        });

        it('counts exactly 12 HUGS levels (excludes Power Team Gym HUGS)', function () {
            // isHugs() returns true for GGH* prefix or value containing 'HUGS'
            // Power Team Gym HUGS levels have GGTH prefix and don't contain 'HUGS' in value
            $hugsLevels = array_filter(
                GfaLevel::cases(),
                fn (GfaLevel $level) => $level->isHugs()
            );
            expect($hugsLevels)->toHaveCount(12);
        });

        it('Power Team Gym and Team Acro are mutually exclusive', function () {
            foreach (GfaLevel::cases() as $level) {
                expect($level->isPowerTeamGym() && $level->isTeamAcro())->toBeFalse();
            }
        });

        it('hugsCategory returns non-null for all HUGS levels', function () {
            foreach (GfaLevel::cases() as $level) {
                if ($level->isHugs()) {
                    expect($level->hugsCategory())->not->toBeNull();
                }
            }
        });

        it('hugsCategory returns null for non-HUGS levels', function () {
            foreach (GfaLevel::cases() as $level) {
                if (!$level->isHugs()) {
                    expect($level->hugsCategory())->toBeNull();
                }
            }
        });

        it('all HUGS categories are valid', function () {
            $validCategories = ['Women', 'Men', 'Rhythmic', 'T&T', 'Power Team Gym'];

            foreach (GfaLevel::cases() as $level) {
                $category = $level->hugsCategory();
                if ($category !== null) {
                    expect($validCategories)->toContain($category);
                }
            }
        });
    });
});
