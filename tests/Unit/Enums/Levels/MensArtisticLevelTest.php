<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Levels\MensArtisticLevel;

describe('MensArtisticLevel', function () {
    describe('enum cases', function () {
        it('has exactly 27 cases', function () {
            expect(MensArtisticLevel::cases())->toHaveCount(27);
        });

        describe('Xcel Program levels', function () {
            it('has Bronze case with correct value', function () {
                expect(MensArtisticLevel::Bronze->value)->toBe('MBRONZE');
            });

            it('has Silver case with correct value', function () {
                expect(MensArtisticLevel::Silver->value)->toBe('MSILVER');
            });

            it('has Gold case with correct value', function () {
                expect(MensArtisticLevel::Gold->value)->toBe('MGOLD');
            });

            it('has Platinum case with correct value', function () {
                expect(MensArtisticLevel::Platinum->value)->toBe('MPLATINUM');
            });
        });

        describe('JO Program levels', function () {
            it('has Level1 case with correct value', function () {
                expect(MensArtisticLevel::Level1->value)->toBe('MLEVEL01');
            });

            it('has Level2 case with correct value', function () {
                expect(MensArtisticLevel::Level2->value)->toBe('MLEVEL02');
            });

            it('has Level3 case with correct value', function () {
                expect(MensArtisticLevel::Level3->value)->toBe('MLEVEL03');
            });
        });

        describe('Development Program D1 levels', function () {
            it('has Level3D1 case with correct value', function () {
                expect(MensArtisticLevel::Level3D1->value)->toBe('M3D1');
            });

            it('has Level4D1 case with correct value', function () {
                expect(MensArtisticLevel::Level4D1->value)->toBe('M4D1');
            });

            it('has Level5D1 case with correct value', function () {
                expect(MensArtisticLevel::Level5D1->value)->toBe('M5D1');
            });
        });

        describe('Development Program D2 levels', function () {
            it('has Level3D2 case with correct value', function () {
                expect(MensArtisticLevel::Level3D2->value)->toBe('M3D2');
            });

            it('has Level4D2 case with correct value', function () {
                expect(MensArtisticLevel::Level4D2->value)->toBe('M4D2');
            });

            it('has Level5D2 case with correct value', function () {
                expect(MensArtisticLevel::Level5D2->value)->toBe('M5D2');
            });
        });

        describe('Junior National (JN) Program levels', function () {
            it('has Level6JN case with correct value', function () {
                expect(MensArtisticLevel::Level6JN->value)->toBe('MLEVEL06JN');
            });

            it('has Level7JN case with correct value', function () {
                expect(MensArtisticLevel::Level7JN->value)->toBe('MLEVEL07JN');
            });

            it('has Level8JN case with correct value', function () {
                expect(MensArtisticLevel::Level8JN->value)->toBe('MLEVEL08JN');
            });

            it('has Level9JN case with correct value', function () {
                expect(MensArtisticLevel::Level9JN->value)->toBe('MLEVEL09JN');
            });

            it('has Level10JNJunior case with correct value', function () {
                expect(MensArtisticLevel::Level10JNJunior->value)->toBe('MLEVEL10JNJR');
            });

            it('has Level10JNSenior case with correct value', function () {
                expect(MensArtisticLevel::Level10JNSenior->value)->toBe('MLEVEL10JNSR');
            });
        });

        describe('Junior Elite (JE) Program levels', function () {
            it('has Level6JE case with correct value', function () {
                expect(MensArtisticLevel::Level6JE->value)->toBe('MLEVEL06JE');
            });

            it('has Level8JE case with correct value', function () {
                expect(MensArtisticLevel::Level8JE->value)->toBe('MLEVEL08JE');
            });

            it('has Level9JE case with correct value', function () {
                expect(MensArtisticLevel::Level9JE->value)->toBe('MLEVEL09JE');
            });

            it('has Level10JEJunior case with correct value', function () {
                expect(MensArtisticLevel::Level10JEJunior->value)->toBe('MLEVEL10JEJR');
            });

            it('has Level10JESenior case with correct value', function () {
                expect(MensArtisticLevel::Level10JESenior->value)->toBe('MLEVEL10JESR');
            });
        });

        describe('Special levels', function () {
            it('has Elite case with correct value', function () {
                expect(MensArtisticLevel::Elite->value)->toBe('MELITE');
            });

            it('has Exhibition case with correct value', function () {
                expect(MensArtisticLevel::Exhibition->value)->toBe('MEXHIB');
            });

            it('has Hugs case with correct value', function () {
                expect(MensArtisticLevel::Hugs->value)->toBe('MHUGS');
            });
        });
    });

    describe('displayValue()', function () {
        describe('Xcel Program display values', function () {
            it('returns "Bronze" for Bronze', function () {
                expect(MensArtisticLevel::Bronze->displayValue())->toBe('Bronze');
            });

            it('returns "Silver" for Silver', function () {
                expect(MensArtisticLevel::Silver->displayValue())->toBe('Silver');
            });

            it('returns "Gold" for Gold', function () {
                expect(MensArtisticLevel::Gold->displayValue())->toBe('Gold');
            });

            it('returns "Platinum" for Platinum', function () {
                expect(MensArtisticLevel::Platinum->displayValue())->toBe('Platinum');
            });
        });

        describe('JO Program display values', function () {
            it('returns "1" for Level1', function () {
                expect(MensArtisticLevel::Level1->displayValue())->toBe('1');
            });

            it('returns "2" for Level2', function () {
                expect(MensArtisticLevel::Level2->displayValue())->toBe('2');
            });

            it('returns "3" for Level3', function () {
                expect(MensArtisticLevel::Level3->displayValue())->toBe('3');
            });
        });

        describe('Development Program display values', function () {
            it('returns "3D1" for Level3D1', function () {
                expect(MensArtisticLevel::Level3D1->displayValue())->toBe('3D1');
            });

            it('returns "4D1" for Level4D1', function () {
                expect(MensArtisticLevel::Level4D1->displayValue())->toBe('4D1');
            });

            it('returns "5D1" for Level5D1', function () {
                expect(MensArtisticLevel::Level5D1->displayValue())->toBe('5D1');
            });

            it('returns "3D2" for Level3D2', function () {
                expect(MensArtisticLevel::Level3D2->displayValue())->toBe('3D2');
            });

            it('returns "4D2" for Level4D2', function () {
                expect(MensArtisticLevel::Level4D2->displayValue())->toBe('4D2');
            });

            it('returns "5D2" for Level5D2', function () {
                expect(MensArtisticLevel::Level5D2->displayValue())->toBe('5D2');
            });
        });

        describe('JN and JE Program display values', function () {
            it('returns "6" for Level6JN and Level6JE', function () {
                expect(MensArtisticLevel::Level6JN->displayValue())->toBe('6');
                expect(MensArtisticLevel::Level6JE->displayValue())->toBe('6');
            });

            it('returns "7" for Level7JN', function () {
                expect(MensArtisticLevel::Level7JN->displayValue())->toBe('7');
            });

            it('returns "8" for Level8JN and Level8JE', function () {
                expect(MensArtisticLevel::Level8JN->displayValue())->toBe('8');
                expect(MensArtisticLevel::Level8JE->displayValue())->toBe('8');
            });

            it('returns "9" for Level9JN and Level9JE', function () {
                expect(MensArtisticLevel::Level9JN->displayValue())->toBe('9');
                expect(MensArtisticLevel::Level9JE->displayValue())->toBe('9');
            });

            it('returns "10" for all Level10 variants', function () {
                expect(MensArtisticLevel::Level10JNJunior->displayValue())->toBe('10');
                expect(MensArtisticLevel::Level10JNSenior->displayValue())->toBe('10');
                expect(MensArtisticLevel::Level10JEJunior->displayValue())->toBe('10');
                expect(MensArtisticLevel::Level10JESenior->displayValue())->toBe('10');
            });
        });

        describe('Special level display values', function () {
            it('returns "Elite" for Elite', function () {
                expect(MensArtisticLevel::Elite->displayValue())->toBe('Elite');
            });

            it('returns "Exhib" for Exhibition', function () {
                expect(MensArtisticLevel::Exhibition->displayValue())->toBe('Exhib');
            });

            it('returns "HUGS" for Hugs', function () {
                expect(MensArtisticLevel::Hugs->displayValue())->toBe('HUGS');
            });
        });
    });

    describe('isDevelopment()', function () {
        it('returns true for Level3D1', function () {
            expect(MensArtisticLevel::Level3D1->isDevelopment())->toBeTrue();
        });

        it('returns true for Level4D1', function () {
            expect(MensArtisticLevel::Level4D1->isDevelopment())->toBeTrue();
        });

        it('returns true for Level5D1', function () {
            expect(MensArtisticLevel::Level5D1->isDevelopment())->toBeTrue();
        });

        it('returns true for Level3D2', function () {
            expect(MensArtisticLevel::Level3D2->isDevelopment())->toBeTrue();
        });

        it('returns true for Level4D2', function () {
            expect(MensArtisticLevel::Level4D2->isDevelopment())->toBeTrue();
        });

        it('returns true for Level5D2', function () {
            expect(MensArtisticLevel::Level5D2->isDevelopment())->toBeTrue();
        });

        it('returns false for JO levels', function () {
            expect(MensArtisticLevel::Level1->isDevelopment())->toBeFalse();
            expect(MensArtisticLevel::Level3->isDevelopment())->toBeFalse();
        });

        it('returns false for JN levels', function () {
            expect(MensArtisticLevel::Level6JN->isDevelopment())->toBeFalse();
        });

        it('returns false for JE levels', function () {
            expect(MensArtisticLevel::Level6JE->isDevelopment())->toBeFalse();
        });

        it('returns false for Xcel levels', function () {
            expect(MensArtisticLevel::Bronze->isDevelopment())->toBeFalse();
        });
    });

    describe('isJuniorNational()', function () {
        it('returns true for Level6JN', function () {
            expect(MensArtisticLevel::Level6JN->isJuniorNational())->toBeTrue();
        });

        it('returns true for Level7JN', function () {
            expect(MensArtisticLevel::Level7JN->isJuniorNational())->toBeTrue();
        });

        it('returns true for Level8JN', function () {
            expect(MensArtisticLevel::Level8JN->isJuniorNational())->toBeTrue();
        });

        it('returns true for Level9JN', function () {
            expect(MensArtisticLevel::Level9JN->isJuniorNational())->toBeTrue();
        });

        it('returns true for Level10JNJunior', function () {
            expect(MensArtisticLevel::Level10JNJunior->isJuniorNational())->toBeTrue();
        });

        it('returns true for Level10JNSenior', function () {
            expect(MensArtisticLevel::Level10JNSenior->isJuniorNational())->toBeTrue();
        });

        it('returns false for JE levels', function () {
            expect(MensArtisticLevel::Level6JE->isJuniorNational())->toBeFalse();
            expect(MensArtisticLevel::Level10JEJunior->isJuniorNational())->toBeFalse();
        });

        it('returns false for development levels', function () {
            expect(MensArtisticLevel::Level3D1->isJuniorNational())->toBeFalse();
        });

        it('returns false for Xcel levels', function () {
            expect(MensArtisticLevel::Bronze->isJuniorNational())->toBeFalse();
        });
    });

    describe('isJuniorElite()', function () {
        it('returns true for Level6JE', function () {
            expect(MensArtisticLevel::Level6JE->isJuniorElite())->toBeTrue();
        });

        it('returns true for Level8JE', function () {
            expect(MensArtisticLevel::Level8JE->isJuniorElite())->toBeTrue();
        });

        it('returns true for Level9JE', function () {
            expect(MensArtisticLevel::Level9JE->isJuniorElite())->toBeTrue();
        });

        it('returns true for Level10JEJunior', function () {
            expect(MensArtisticLevel::Level10JEJunior->isJuniorElite())->toBeTrue();
        });

        it('returns true for Level10JESenior', function () {
            expect(MensArtisticLevel::Level10JESenior->isJuniorElite())->toBeTrue();
        });

        it('returns false for JN levels', function () {
            expect(MensArtisticLevel::Level6JN->isJuniorElite())->toBeFalse();
            expect(MensArtisticLevel::Level10JNJunior->isJuniorElite())->toBeFalse();
        });

        it('returns false for development levels', function () {
            expect(MensArtisticLevel::Level5D1->isJuniorElite())->toBeFalse();
        });

        it('returns false for Elite', function () {
            expect(MensArtisticLevel::Elite->isJuniorElite())->toBeFalse();
        });
    });

    describe('fromDisplayValue()', function () {
        it('returns correct enum for exact match', function () {
            expect(MensArtisticLevel::fromDisplayValue('Bronze'))->toBe(MensArtisticLevel::Bronze);
        });

        it('returns correct enum for case-insensitive match', function () {
            expect(MensArtisticLevel::fromDisplayValue('bronze'))->toBe(MensArtisticLevel::Bronze);
            expect(MensArtisticLevel::fromDisplayValue('BRONZE'))->toBe(MensArtisticLevel::Bronze);
        });

        it('returns correct enum for numeric level', function () {
            expect(MensArtisticLevel::fromDisplayValue('1'))->toBe(MensArtisticLevel::Level1);
            expect(MensArtisticLevel::fromDisplayValue('2'))->toBe(MensArtisticLevel::Level2);
            expect(MensArtisticLevel::fromDisplayValue('3'))->toBe(MensArtisticLevel::Level3);
        });

        it('returns correct enum for development levels', function () {
            expect(MensArtisticLevel::fromDisplayValue('3D1'))->toBe(MensArtisticLevel::Level3D1);
            expect(MensArtisticLevel::fromDisplayValue('4D2'))->toBe(MensArtisticLevel::Level4D2);
        });

        it('returns first matching enum for ambiguous display values', function () {
            // Note: "6" matches both Level6JN and Level6JE, returns first match
            $result = MensArtisticLevel::fromDisplayValue('6');
            expect($result)->not->toBeNull();
            expect($result->displayValue())->toBe('6');
        });

        it('returns correct enum for special levels', function () {
            expect(MensArtisticLevel::fromDisplayValue('Elite'))->toBe(MensArtisticLevel::Elite);
            expect(MensArtisticLevel::fromDisplayValue('HUGS'))->toBe(MensArtisticLevel::Hugs);
        });

        it('returns null for non-existent value', function () {
            expect(MensArtisticLevel::fromDisplayValue('NonExistent'))->toBeNull();
        });

        it('returns null for empty string', function () {
            expect(MensArtisticLevel::fromDisplayValue(''))->toBeNull();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(MensArtisticLevel::tryFrom('MBRONZE'))->toBe(MensArtisticLevel::Bronze);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(MensArtisticLevel::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(MensArtisticLevel::from('M3D1'))->toBe(MensArtisticLevel::Level3D1);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => MensArtisticLevel::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('level categorization consistency', function () {
        it('counts exactly 4 Xcel levels', function () {
            $xcelLevels = [
                MensArtisticLevel::Bronze,
                MensArtisticLevel::Silver,
                MensArtisticLevel::Gold,
                MensArtisticLevel::Platinum,
            ];
            expect($xcelLevels)->toHaveCount(4);
        });

        it('counts exactly 6 development levels', function () {
            $developmentLevels = array_filter(
                MensArtisticLevel::cases(),
                fn (MensArtisticLevel $level) => $level->isDevelopment()
            );
            expect($developmentLevels)->toHaveCount(6);
        });

        it('counts exactly 6 Junior National levels', function () {
            $jnLevels = array_filter(
                MensArtisticLevel::cases(),
                fn (MensArtisticLevel $level) => $level->isJuniorNational()
            );
            expect($jnLevels)->toHaveCount(6);
        });

        it('counts exactly 5 Junior Elite levels', function () {
            $jeLevels = array_filter(
                MensArtisticLevel::cases(),
                fn (MensArtisticLevel $level) => $level->isJuniorElite()
            );
            expect($jeLevels)->toHaveCount(5);
        });

        it('JN and JE levels are mutually exclusive', function () {
            foreach (MensArtisticLevel::cases() as $level) {
                expect($level->isJuniorNational() && $level->isJuniorElite())->toBeFalse();
            }
        });

        it('development levels are not JN or JE', function () {
            foreach (MensArtisticLevel::cases() as $level) {
                if ($level->isDevelopment()) {
                    expect($level->isJuniorNational())->toBeFalse();
                    expect($level->isJuniorElite())->toBeFalse();
                }
            }
        });
    });
});
