<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Levels\RhythmicLevel;

describe('RhythmicLevel', function () {
    describe('enum cases', function () {
        it('has exactly 28 cases', function () {
            expect(RhythmicLevel::cases())->toHaveCount(28);
        });

        describe('Individual levels', function () {
            it('has SuperStars case with correct value', function () {
                expect(RhythmicLevel::SuperStars->value)->toBe('RSS');
            });

            it('has Copper case with correct value', function () {
                expect(RhythmicLevel::Copper->value)->toBe('RCOPPER');
            });

            it('has Bronze case with correct value', function () {
                expect(RhythmicLevel::Bronze->value)->toBe('RBRONZE');
            });

            it('has Silver case with correct value', function () {
                expect(RhythmicLevel::Silver->value)->toBe('RSILVER');
            });

            it('has Gold case with correct value', function () {
                expect(RhythmicLevel::Gold->value)->toBe('RGOLD');
            });

            it('has Diamond case with correct value', function () {
                expect(RhythmicLevel::Diamond->value)->toBe('RDIAMOND');
            });

            it('has Platinum case with correct value', function () {
                expect(RhythmicLevel::Platinum->value)->toBe('RPLATINUM');
            });

            it('has Level1 through Level10 with correct values', function () {
                expect(RhythmicLevel::Level1->value)->toBe('RLEVEL01');
                expect(RhythmicLevel::Level2->value)->toBe('RLEVEL02');
                expect(RhythmicLevel::Level3->value)->toBe('RLEVEL03');
                expect(RhythmicLevel::Level4->value)->toBe('RLEVEL04');
                expect(RhythmicLevel::Level5->value)->toBe('RLEVEL05');
                expect(RhythmicLevel::Level6->value)->toBe('RLEVEL06');
                expect(RhythmicLevel::Level7->value)->toBe('RLEVEL07');
                expect(RhythmicLevel::Level8->value)->toBe('RLEVEL08');
                expect(RhythmicLevel::Level9->value)->toBe('RLEVEL09');
                expect(RhythmicLevel::Level10->value)->toBe('RLEVEL10');
            });

            it('has Elite case with correct value', function () {
                expect(RhythmicLevel::Elite->value)->toBe('RELITE');
            });

            it('has Exhibition case with correct value', function () {
                expect(RhythmicLevel::Exhibition->value)->toBe('REXHIBI');
            });

            it('has Hugs case with correct value', function () {
                expect(RhythmicLevel::Hugs->value)->toBe('RHUGS');
            });
        });

        describe('Group levels', function () {
            it('has GroupLevel4 case with correct value', function () {
                expect(RhythmicLevel::GroupLevel4->value)->toBe('RGLEVEL04');
            });

            it('has GroupLevel5 case with correct value', function () {
                expect(RhythmicLevel::GroupLevel5->value)->toBe('RGLEVEL05');
            });

            it('has GroupLevel6 case with correct value', function () {
                expect(RhythmicLevel::GroupLevel6->value)->toBe('RGLEVEL06');
            });

            it('has GroupBeginner case with correct value', function () {
                expect(RhythmicLevel::GroupBeginner->value)->toBe('RGBEGINNER');
            });

            it('has GroupIntermediate case with correct value', function () {
                expect(RhythmicLevel::GroupIntermediate->value)->toBe('RGINTERMED');
            });

            it('has GroupAdvanced case with correct value', function () {
                expect(RhythmicLevel::GroupAdvanced->value)->toBe('RGADVANCED');
            });

            it('has FigJunior case with correct value', function () {
                expect(RhythmicLevel::FigJunior->value)->toBe('RFIGJR');
            });

            it('has FigSenior case with correct value', function () {
                expect(RhythmicLevel::FigSenior->value)->toBe('RFIGSR');
            });
        });
    });

    describe('displayValue()', function () {
        describe('Individual level display values', function () {
            it('returns "SuperStars" for SuperStars', function () {
                expect(RhythmicLevel::SuperStars->displayValue())->toBe('SuperStars');
            });

            it('returns "Copper" for Copper', function () {
                expect(RhythmicLevel::Copper->displayValue())->toBe('Copper');
            });

            it('returns "Bronze" for Bronze', function () {
                expect(RhythmicLevel::Bronze->displayValue())->toBe('Bronze');
            });

            it('returns "Silver" for Silver', function () {
                expect(RhythmicLevel::Silver->displayValue())->toBe('Silver');
            });

            it('returns "Gold" for Gold', function () {
                expect(RhythmicLevel::Gold->displayValue())->toBe('Gold');
            });

            it('returns "Diamond" for Diamond', function () {
                expect(RhythmicLevel::Diamond->displayValue())->toBe('Diamond');
            });

            it('returns "Platinum" for Platinum', function () {
                expect(RhythmicLevel::Platinum->displayValue())->toBe('Platinum');
            });

            it('returns numeric values for individual levels', function () {
                expect(RhythmicLevel::Level1->displayValue())->toBe('1');
                expect(RhythmicLevel::Level5->displayValue())->toBe('5');
                expect(RhythmicLevel::Level10->displayValue())->toBe('10');
            });

            it('returns "Elite" for Elite', function () {
                expect(RhythmicLevel::Elite->displayValue())->toBe('Elite');
            });

            it('returns "Exhib" for Exhibition', function () {
                expect(RhythmicLevel::Exhibition->displayValue())->toBe('Exhib');
            });

            it('returns "HUGS" for Hugs', function () {
                expect(RhythmicLevel::Hugs->displayValue())->toBe('HUGS');
            });
        });

        describe('Group level display values', function () {
            it('returns "4" for GroupLevel4', function () {
                expect(RhythmicLevel::GroupLevel4->displayValue())->toBe('4');
            });

            it('returns "5" for GroupLevel5', function () {
                expect(RhythmicLevel::GroupLevel5->displayValue())->toBe('5');
            });

            it('returns "6" for GroupLevel6', function () {
                expect(RhythmicLevel::GroupLevel6->displayValue())->toBe('6');
            });

            it('returns "Beginner" for GroupBeginner', function () {
                expect(RhythmicLevel::GroupBeginner->displayValue())->toBe('Beginner');
            });

            it('returns "Intermediate" for GroupIntermediate', function () {
                expect(RhythmicLevel::GroupIntermediate->displayValue())->toBe('Intermediate');
            });

            it('returns "Advanced" for GroupAdvanced', function () {
                expect(RhythmicLevel::GroupAdvanced->displayValue())->toBe('Advanced');
            });

            it('returns "FIG Jr." for FigJunior', function () {
                expect(RhythmicLevel::FigJunior->displayValue())->toBe('FIG Jr.');
            });

            it('returns "FIG Sr." for FigSenior', function () {
                expect(RhythmicLevel::FigSenior->displayValue())->toBe('FIG Sr.');
            });
        });
    });

    describe('isGroupLevel()', function () {
        it('returns true for GroupLevel4', function () {
            expect(RhythmicLevel::GroupLevel4->isGroupLevel())->toBeTrue();
        });

        it('returns true for GroupLevel5', function () {
            expect(RhythmicLevel::GroupLevel5->isGroupLevel())->toBeTrue();
        });

        it('returns true for GroupLevel6', function () {
            expect(RhythmicLevel::GroupLevel6->isGroupLevel())->toBeTrue();
        });

        it('returns true for GroupBeginner', function () {
            expect(RhythmicLevel::GroupBeginner->isGroupLevel())->toBeTrue();
        });

        it('returns true for GroupIntermediate', function () {
            expect(RhythmicLevel::GroupIntermediate->isGroupLevel())->toBeTrue();
        });

        it('returns true for GroupAdvanced', function () {
            expect(RhythmicLevel::GroupAdvanced->isGroupLevel())->toBeTrue();
        });

        it('returns true for FigJunior', function () {
            expect(RhythmicLevel::FigJunior->isGroupLevel())->toBeTrue();
        });

        it('returns true for FigSenior', function () {
            expect(RhythmicLevel::FigSenior->isGroupLevel())->toBeTrue();
        });

        it('returns false for individual levels', function () {
            expect(RhythmicLevel::SuperStars->isGroupLevel())->toBeFalse();
            expect(RhythmicLevel::Level1->isGroupLevel())->toBeFalse();
            expect(RhythmicLevel::Elite->isGroupLevel())->toBeFalse();
            expect(RhythmicLevel::Bronze->isGroupLevel())->toBeFalse();
        });
    });

    describe('isIndividualLevel()', function () {
        it('returns true for SuperStars', function () {
            expect(RhythmicLevel::SuperStars->isIndividualLevel())->toBeTrue();
        });

        it('returns true for Copper', function () {
            expect(RhythmicLevel::Copper->isIndividualLevel())->toBeTrue();
        });

        it('returns true for numeric levels', function () {
            expect(RhythmicLevel::Level1->isIndividualLevel())->toBeTrue();
            expect(RhythmicLevel::Level5->isIndividualLevel())->toBeTrue();
            expect(RhythmicLevel::Level10->isIndividualLevel())->toBeTrue();
        });

        it('returns true for Elite', function () {
            expect(RhythmicLevel::Elite->isIndividualLevel())->toBeTrue();
        });

        it('returns true for Exhibition', function () {
            expect(RhythmicLevel::Exhibition->isIndividualLevel())->toBeTrue();
        });

        it('returns true for Hugs', function () {
            expect(RhythmicLevel::Hugs->isIndividualLevel())->toBeTrue();
        });

        it('returns false for group levels', function () {
            expect(RhythmicLevel::GroupLevel4->isIndividualLevel())->toBeFalse();
            expect(RhythmicLevel::GroupBeginner->isIndividualLevel())->toBeFalse();
            expect(RhythmicLevel::FigJunior->isIndividualLevel())->toBeFalse();
        });
    });

    describe('fromDisplayValue()', function () {
        it('returns correct enum for exact match', function () {
            expect(RhythmicLevel::fromDisplayValue('SuperStars'))->toBe(RhythmicLevel::SuperStars);
            expect(RhythmicLevel::fromDisplayValue('Copper'))->toBe(RhythmicLevel::Copper);
            expect(RhythmicLevel::fromDisplayValue('Bronze'))->toBe(RhythmicLevel::Bronze);
        });

        it('returns correct enum for case-insensitive match', function () {
            expect(RhythmicLevel::fromDisplayValue('superstars'))->toBe(RhythmicLevel::SuperStars);
            expect(RhythmicLevel::fromDisplayValue('COPPER'))->toBe(RhythmicLevel::Copper);
        });

        it('returns correct enum for numeric level', function () {
            expect(RhythmicLevel::fromDisplayValue('1'))->toBe(RhythmicLevel::Level1);
            expect(RhythmicLevel::fromDisplayValue('10'))->toBe(RhythmicLevel::Level10);
        });

        it('returns correct enum for group levels', function () {
            expect(RhythmicLevel::fromDisplayValue('Beginner'))->toBe(RhythmicLevel::GroupBeginner);
            expect(RhythmicLevel::fromDisplayValue('Intermediate'))->toBe(RhythmicLevel::GroupIntermediate);
            expect(RhythmicLevel::fromDisplayValue('Advanced'))->toBe(RhythmicLevel::GroupAdvanced);
        });

        it('returns correct enum for FIG levels', function () {
            expect(RhythmicLevel::fromDisplayValue('FIG Jr.'))->toBe(RhythmicLevel::FigJunior);
            expect(RhythmicLevel::fromDisplayValue('FIG Sr.'))->toBe(RhythmicLevel::FigSenior);
        });

        it('returns first matching enum for ambiguous display values', function () {
            // Note: "4" matches both Level4 and GroupLevel4, returns first match
            $result = RhythmicLevel::fromDisplayValue('4');
            expect($result)->not->toBeNull();
            expect($result->displayValue())->toBe('4');
        });

        it('returns null for non-existent value', function () {
            expect(RhythmicLevel::fromDisplayValue('NonExistent'))->toBeNull();
        });

        it('returns null for empty string', function () {
            expect(RhythmicLevel::fromDisplayValue(''))->toBeNull();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(RhythmicLevel::tryFrom('RSS'))->toBe(RhythmicLevel::SuperStars);
            expect(RhythmicLevel::tryFrom('RGLEVEL04'))->toBe(RhythmicLevel::GroupLevel4);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(RhythmicLevel::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(RhythmicLevel::from('RBRONZE'))->toBe(RhythmicLevel::Bronze);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => RhythmicLevel::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('level categorization consistency', function () {
        it('counts exactly 8 group levels', function () {
            $groupLevels = array_filter(
                RhythmicLevel::cases(),
                fn (RhythmicLevel $level) => $level->isGroupLevel()
            );
            expect($groupLevels)->toHaveCount(8);
        });

        it('counts exactly 20 individual levels', function () {
            $individualLevels = array_filter(
                RhythmicLevel::cases(),
                fn (RhythmicLevel $level) => $level->isIndividualLevel()
            );
            expect($individualLevels)->toHaveCount(20);
        });

        it('every level is either group or individual', function () {
            foreach (RhythmicLevel::cases() as $level) {
                expect($level->isGroupLevel() xor $level->isIndividualLevel())->toBeTrue();
            }
        });

        it('group and individual are mutually exclusive', function () {
            foreach (RhythmicLevel::cases() as $level) {
                expect($level->isGroupLevel() && $level->isIndividualLevel())->toBeFalse();
            }
        });
    });
});
