<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Levels\WomensArtisticLevel;

describe('WomensArtisticLevel', function () {
    describe('enum cases', function () {
        it('has exactly 22 cases', function () {
            expect(WomensArtisticLevel::cases())->toHaveCount(22);
        });

        describe('Xcel Program levels', function () {
            it('has Bronze case with correct value', function () {
                expect(WomensArtisticLevel::Bronze->value)->toBe('WBRONZE');
            });

            it('has Silver case with correct value', function () {
                expect(WomensArtisticLevel::Silver->value)->toBe('WSILVER');
            });

            it('has Gold case with correct value', function () {
                expect(WomensArtisticLevel::Gold->value)->toBe('WGOLD');
            });

            it('has Platinum case with correct value', function () {
                expect(WomensArtisticLevel::Platinum->value)->toBe('WPLATINUM');
            });

            it('has Diamond case with correct value', function () {
                expect(WomensArtisticLevel::Diamond->value)->toBe('WDIAMOND');
            });

            it('has Sapphire case with correct value', function () {
                expect(WomensArtisticLevel::Sapphire->value)->toBe('WSAPPHIRE');
            });
        });

        describe('JO Program levels', function () {
            it('has Level1 case with correct value', function () {
                expect(WomensArtisticLevel::Level1->value)->toBe('WLEVEL01');
            });

            it('has Level2 case with correct value', function () {
                expect(WomensArtisticLevel::Level2->value)->toBe('WLEVEL02');
            });

            it('has Level3 case with correct value', function () {
                expect(WomensArtisticLevel::Level3->value)->toBe('WLEVEL03');
            });

            it('has Level4 case with correct value', function () {
                expect(WomensArtisticLevel::Level4->value)->toBe('WLEVEL04');
            });

            it('has Level5 case with correct value', function () {
                expect(WomensArtisticLevel::Level5->value)->toBe('WLEVEL05');
            });

            it('has Level6 case with correct value', function () {
                expect(WomensArtisticLevel::Level6->value)->toBe('WLEVEL06');
            });

            it('has Level7 case with correct value', function () {
                expect(WomensArtisticLevel::Level7->value)->toBe('WLEVEL07');
            });

            it('has Level8 case with correct value', function () {
                expect(WomensArtisticLevel::Level8->value)->toBe('WLEVEL08');
            });

            it('has Level9 case with correct value', function () {
                expect(WomensArtisticLevel::Level9->value)->toBe('WLEVEL09');
            });

            it('has Level10 case with correct value', function () {
                expect(WomensArtisticLevel::Level10->value)->toBe('WLEVEL10');
            });
        });

        describe('Special levels', function () {
            it('has Open case with correct value', function () {
                expect(WomensArtisticLevel::Open->value)->toBe('WOPEN');
            });

            it('has Elite case with correct value', function () {
                expect(WomensArtisticLevel::Elite->value)->toBe('WELITE');
            });

            it('has Tops case with correct value', function () {
                expect(WomensArtisticLevel::Tops->value)->toBe('WTOPS');
            });

            it('has Exhibition case with correct value', function () {
                expect(WomensArtisticLevel::Exhibition->value)->toBe('WEXHIB');
            });

            it('has Hopes case with correct value', function () {
                expect(WomensArtisticLevel::Hopes->value)->toBe('WHOPES');
            });

            it('has Hugs case with correct value', function () {
                expect(WomensArtisticLevel::Hugs->value)->toBe('WHUGS');
            });
        });
    });

    describe('displayValue()', function () {
        describe('Xcel Program display values', function () {
            it('returns "Bronze" for Bronze', function () {
                expect(WomensArtisticLevel::Bronze->displayValue())->toBe('Bronze');
            });

            it('returns "Silver" for Silver', function () {
                expect(WomensArtisticLevel::Silver->displayValue())->toBe('Silver');
            });

            it('returns "Gold" for Gold', function () {
                expect(WomensArtisticLevel::Gold->displayValue())->toBe('Gold');
            });

            it('returns "Platinum" for Platinum', function () {
                expect(WomensArtisticLevel::Platinum->displayValue())->toBe('Platinum');
            });

            it('returns "Diamond" for Diamond', function () {
                expect(WomensArtisticLevel::Diamond->displayValue())->toBe('Diamond');
            });

            it('returns "Sapphire" for Sapphire', function () {
                expect(WomensArtisticLevel::Sapphire->displayValue())->toBe('Sapphire');
            });
        });

        describe('JO Program display values', function () {
            it('returns "1" for Level1', function () {
                expect(WomensArtisticLevel::Level1->displayValue())->toBe('1');
            });

            it('returns "2" for Level2', function () {
                expect(WomensArtisticLevel::Level2->displayValue())->toBe('2');
            });

            it('returns "3" for Level3', function () {
                expect(WomensArtisticLevel::Level3->displayValue())->toBe('3');
            });

            it('returns "4" for Level4', function () {
                expect(WomensArtisticLevel::Level4->displayValue())->toBe('4');
            });

            it('returns "5" for Level5', function () {
                expect(WomensArtisticLevel::Level5->displayValue())->toBe('5');
            });

            it('returns "6" for Level6', function () {
                expect(WomensArtisticLevel::Level6->displayValue())->toBe('6');
            });

            it('returns "7" for Level7', function () {
                expect(WomensArtisticLevel::Level7->displayValue())->toBe('7');
            });

            it('returns "8" for Level8', function () {
                expect(WomensArtisticLevel::Level8->displayValue())->toBe('8');
            });

            it('returns "9" for Level9', function () {
                expect(WomensArtisticLevel::Level9->displayValue())->toBe('9');
            });

            it('returns "10" for Level10', function () {
                expect(WomensArtisticLevel::Level10->displayValue())->toBe('10');
            });
        });

        describe('Special level display values', function () {
            it('returns "Open" for Open', function () {
                expect(WomensArtisticLevel::Open->displayValue())->toBe('Open');
            });

            it('returns "Elite" for Elite', function () {
                expect(WomensArtisticLevel::Elite->displayValue())->toBe('Elite');
            });

            it('returns "TOPS" for Tops', function () {
                expect(WomensArtisticLevel::Tops->displayValue())->toBe('TOPS');
            });

            it('returns "Exhib Camp" for Exhibition', function () {
                expect(WomensArtisticLevel::Exhibition->displayValue())->toBe('Exhib Camp');
            });

            it('returns "Hopes" for Hopes', function () {
                expect(WomensArtisticLevel::Hopes->displayValue())->toBe('Hopes');
            });

            it('returns "HUGS" for Hugs', function () {
                expect(WomensArtisticLevel::Hugs->displayValue())->toBe('HUGS');
            });
        });
    });

    describe('isCompulsory()', function () {
        it('returns true for Level1', function () {
            expect(WomensArtisticLevel::Level1->isCompulsory())->toBeTrue();
        });

        it('returns true for Level2', function () {
            expect(WomensArtisticLevel::Level2->isCompulsory())->toBeTrue();
        });

        it('returns true for Level3', function () {
            expect(WomensArtisticLevel::Level3->isCompulsory())->toBeTrue();
        });

        it('returns true for Level4', function () {
            expect(WomensArtisticLevel::Level4->isCompulsory())->toBeTrue();
        });

        it('returns true for Level5', function () {
            expect(WomensArtisticLevel::Level5->isCompulsory())->toBeTrue();
        });

        it('returns false for Level6', function () {
            expect(WomensArtisticLevel::Level6->isCompulsory())->toBeFalse();
        });

        it('returns false for Level10', function () {
            expect(WomensArtisticLevel::Level10->isCompulsory())->toBeFalse();
        });

        it('returns false for Xcel levels', function () {
            expect(WomensArtisticLevel::Bronze->isCompulsory())->toBeFalse();
            expect(WomensArtisticLevel::Gold->isCompulsory())->toBeFalse();
        });

        it('returns false for Elite', function () {
            expect(WomensArtisticLevel::Elite->isCompulsory())->toBeFalse();
        });
    });

    describe('isOptional()', function () {
        it('returns true for Level6', function () {
            expect(WomensArtisticLevel::Level6->isOptional())->toBeTrue();
        });

        it('returns true for Level7', function () {
            expect(WomensArtisticLevel::Level7->isOptional())->toBeTrue();
        });

        it('returns true for Level8', function () {
            expect(WomensArtisticLevel::Level8->isOptional())->toBeTrue();
        });

        it('returns true for Level9', function () {
            expect(WomensArtisticLevel::Level9->isOptional())->toBeTrue();
        });

        it('returns true for Level10', function () {
            expect(WomensArtisticLevel::Level10->isOptional())->toBeTrue();
        });

        it('returns false for Level1', function () {
            expect(WomensArtisticLevel::Level1->isOptional())->toBeFalse();
        });

        it('returns false for Level5', function () {
            expect(WomensArtisticLevel::Level5->isOptional())->toBeFalse();
        });

        it('returns false for Xcel levels', function () {
            expect(WomensArtisticLevel::Bronze->isOptional())->toBeFalse();
            expect(WomensArtisticLevel::Platinum->isOptional())->toBeFalse();
        });

        it('returns false for Elite', function () {
            expect(WomensArtisticLevel::Elite->isOptional())->toBeFalse();
        });
    });

    describe('isXcel()', function () {
        it('returns true for Bronze', function () {
            expect(WomensArtisticLevel::Bronze->isXcel())->toBeTrue();
        });

        it('returns true for Silver', function () {
            expect(WomensArtisticLevel::Silver->isXcel())->toBeTrue();
        });

        it('returns true for Gold', function () {
            expect(WomensArtisticLevel::Gold->isXcel())->toBeTrue();
        });

        it('returns true for Platinum', function () {
            expect(WomensArtisticLevel::Platinum->isXcel())->toBeTrue();
        });

        it('returns true for Diamond', function () {
            expect(WomensArtisticLevel::Diamond->isXcel())->toBeTrue();
        });

        it('returns true for Sapphire', function () {
            expect(WomensArtisticLevel::Sapphire->isXcel())->toBeTrue();
        });

        it('returns false for JO levels', function () {
            expect(WomensArtisticLevel::Level1->isXcel())->toBeFalse();
            expect(WomensArtisticLevel::Level5->isXcel())->toBeFalse();
            expect(WomensArtisticLevel::Level10->isXcel())->toBeFalse();
        });

        it('returns false for Elite', function () {
            expect(WomensArtisticLevel::Elite->isXcel())->toBeFalse();
        });
    });

    describe('isElite()', function () {
        it('returns true for Elite', function () {
            expect(WomensArtisticLevel::Elite->isElite())->toBeTrue();
        });

        it('returns true for Hopes', function () {
            expect(WomensArtisticLevel::Hopes->isElite())->toBeTrue();
        });

        it('returns true for Tops', function () {
            expect(WomensArtisticLevel::Tops->isElite())->toBeTrue();
        });

        it('returns false for JO levels', function () {
            expect(WomensArtisticLevel::Level10->isElite())->toBeFalse();
            expect(WomensArtisticLevel::Level1->isElite())->toBeFalse();
        });

        it('returns false for Xcel levels', function () {
            expect(WomensArtisticLevel::Diamond->isElite())->toBeFalse();
        });

        it('returns false for Open', function () {
            expect(WomensArtisticLevel::Open->isElite())->toBeFalse();
        });

        it('returns false for Exhibition', function () {
            expect(WomensArtisticLevel::Exhibition->isElite())->toBeFalse();
        });
    });

    describe('fromDisplayValue()', function () {
        it('returns correct enum for exact match', function () {
            expect(WomensArtisticLevel::fromDisplayValue('Bronze'))->toBe(WomensArtisticLevel::Bronze);
        });

        it('returns correct enum for case-insensitive match', function () {
            expect(WomensArtisticLevel::fromDisplayValue('bronze'))->toBe(WomensArtisticLevel::Bronze);
            expect(WomensArtisticLevel::fromDisplayValue('BRONZE'))->toBe(WomensArtisticLevel::Bronze);
        });

        it('returns correct enum for numeric level', function () {
            expect(WomensArtisticLevel::fromDisplayValue('1'))->toBe(WomensArtisticLevel::Level1);
            expect(WomensArtisticLevel::fromDisplayValue('5'))->toBe(WomensArtisticLevel::Level5);
            expect(WomensArtisticLevel::fromDisplayValue('10'))->toBe(WomensArtisticLevel::Level10);
        });

        it('returns correct enum for special levels', function () {
            expect(WomensArtisticLevel::fromDisplayValue('Elite'))->toBe(WomensArtisticLevel::Elite);
            expect(WomensArtisticLevel::fromDisplayValue('TOPS'))->toBe(WomensArtisticLevel::Tops);
            expect(WomensArtisticLevel::fromDisplayValue('Hopes'))->toBe(WomensArtisticLevel::Hopes);
        });

        it('returns null for non-existent value', function () {
            expect(WomensArtisticLevel::fromDisplayValue('NonExistent'))->toBeNull();
        });

        it('returns null for empty string', function () {
            expect(WomensArtisticLevel::fromDisplayValue(''))->toBeNull();
        });

        it('returns null for invalid numeric level', function () {
            expect(WomensArtisticLevel::fromDisplayValue('11'))->toBeNull();
            expect(WomensArtisticLevel::fromDisplayValue('0'))->toBeNull();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(WomensArtisticLevel::tryFrom('WBRONZE'))->toBe(WomensArtisticLevel::Bronze);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(WomensArtisticLevel::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(WomensArtisticLevel::from('WLEVEL05'))->toBe(WomensArtisticLevel::Level5);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => WomensArtisticLevel::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('level categorization consistency', function () {
        it('counts exactly 6 Xcel levels', function () {
            $xcelLevels = array_filter(
                WomensArtisticLevel::cases(),
                fn (WomensArtisticLevel $level) => $level->isXcel()
            );
            expect($xcelLevels)->toHaveCount(6);
        });

        it('counts exactly 5 compulsory levels', function () {
            $compulsoryLevels = array_filter(
                WomensArtisticLevel::cases(),
                fn (WomensArtisticLevel $level) => $level->isCompulsory()
            );
            expect($compulsoryLevels)->toHaveCount(5);
        });

        it('counts exactly 5 optional levels', function () {
            $optionalLevels = array_filter(
                WomensArtisticLevel::cases(),
                fn (WomensArtisticLevel $level) => $level->isOptional()
            );
            expect($optionalLevels)->toHaveCount(5);
        });

        it('counts exactly 3 elite levels', function () {
            $eliteLevels = array_filter(
                WomensArtisticLevel::cases(),
                fn (WomensArtisticLevel $level) => $level->isElite()
            );
            expect($eliteLevels)->toHaveCount(3);
        });

        it('compulsory and optional levels are mutually exclusive', function () {
            foreach (WomensArtisticLevel::cases() as $level) {
                expect($level->isCompulsory() && $level->isOptional())->toBeFalse();
            }
        });

        it('JO levels are either compulsory or optional', function () {
            $joLevels = [
                WomensArtisticLevel::Level1,
                WomensArtisticLevel::Level2,
                WomensArtisticLevel::Level3,
                WomensArtisticLevel::Level4,
                WomensArtisticLevel::Level5,
                WomensArtisticLevel::Level6,
                WomensArtisticLevel::Level7,
                WomensArtisticLevel::Level8,
                WomensArtisticLevel::Level9,
                WomensArtisticLevel::Level10,
            ];

            foreach ($joLevels as $level) {
                expect($level->isCompulsory() || $level->isOptional())->toBeTrue();
            }
        });
    });
});
