<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Levels\AcrobaticLevel;

describe('AcrobaticLevel', function () {
    describe('enum cases', function () {
        it('has exactly 21 cases', function () {
            expect(AcrobaticLevel::cases())->toHaveCount(21);
        });

        describe('Individual/Pair/Group levels', function () {
            it('has Level1 through Level10 with correct values', function () {
                expect(AcrobaticLevel::Level1->value)->toBe('SLEVEL01');
                expect(AcrobaticLevel::Level2->value)->toBe('SLEVEL02');
                expect(AcrobaticLevel::Level3->value)->toBe('SLEVEL03');
                expect(AcrobaticLevel::Level4->value)->toBe('SLEVEL04');
                expect(AcrobaticLevel::Level5->value)->toBe('SLEVEL05');
                expect(AcrobaticLevel::Level6->value)->toBe('SLEVEL06');
                expect(AcrobaticLevel::Level7->value)->toBe('SLEVEL07');
                expect(AcrobaticLevel::Level8->value)->toBe('SLEVEL08');
                expect(AcrobaticLevel::Level9->value)->toBe('SLEVEL09');
                expect(AcrobaticLevel::Level10->value)->toBe('SLEVEL10');
            });

            it('has Elite case with correct value', function () {
                expect(AcrobaticLevel::Elite->value)->toBe('SELITE');
            });

            it('has Age11To16 case with correct value', function () {
                expect(AcrobaticLevel::Age11To16->value)->toBe('S11-16');
            });

            it('has JuniorElite12To18 case with correct value', function () {
                expect(AcrobaticLevel::JuniorElite12To18->value)->toBe('SJELITE12-18');
            });

            it('has JuniorElite13To19 case with correct value', function () {
                expect(AcrobaticLevel::JuniorElite13To19->value)->toBe('SJELITE13-19');
            });

            it('has SeniorElite case with correct value', function () {
                expect(AcrobaticLevel::SeniorElite->value)->toBe('SSELITE');
            });

            it('has Exhibition case with correct value', function () {
                expect(AcrobaticLevel::Exhibition->value)->toBe('SAEXHIB');
            });
        });

        describe('Block levels', function () {
            it('has BlockBronze case with correct value', function () {
                expect(AcrobaticLevel::BlockBronze->value)->toBe('SGBRONZE');
            });

            it('has BlockSilver case with correct value', function () {
                expect(AcrobaticLevel::BlockSilver->value)->toBe('SGSILVER');
            });

            it('has BlockGold case with correct value', function () {
                expect(AcrobaticLevel::BlockGold->value)->toBe('SGGOLD');
            });

            it('has BlockPlatinum case with correct value', function () {
                expect(AcrobaticLevel::BlockPlatinum->value)->toBe('SGPLATINUM');
            });

            it('has BlockDiamond case with correct value', function () {
                expect(AcrobaticLevel::BlockDiamond->value)->toBe('SGDIAMOND');
            });
        });
    });

    describe('displayValue()', function () {
        describe('Numeric level display values', function () {
            it('returns "1" for Level1', function () {
                expect(AcrobaticLevel::Level1->displayValue())->toBe('1');
            });

            it('returns "5" for Level5', function () {
                expect(AcrobaticLevel::Level5->displayValue())->toBe('5');
            });

            it('returns "10" for Level10', function () {
                expect(AcrobaticLevel::Level10->displayValue())->toBe('10');
            });
        });

        describe('Elite level display values', function () {
            it('returns "Elite" for Elite', function () {
                expect(AcrobaticLevel::Elite->displayValue())->toBe('Elite');
            });

            it('returns "11-16" for Age11To16', function () {
                expect(AcrobaticLevel::Age11To16->displayValue())->toBe('11-16');
            });

            it('returns "JElite12-18" for JuniorElite12To18', function () {
                expect(AcrobaticLevel::JuniorElite12To18->displayValue())->toBe('JElite12-18');
            });

            it('returns "JElite13-19" for JuniorElite13To19', function () {
                expect(AcrobaticLevel::JuniorElite13To19->displayValue())->toBe('JElite13-19');
            });

            it('returns "SElite" for SeniorElite', function () {
                expect(AcrobaticLevel::SeniorElite->displayValue())->toBe('SElite');
            });

            it('returns "Exhib" for Exhibition', function () {
                expect(AcrobaticLevel::Exhibition->displayValue())->toBe('Exhib');
            });
        });

        describe('Block level display values', function () {
            it('returns "Bronze" for BlockBronze', function () {
                expect(AcrobaticLevel::BlockBronze->displayValue())->toBe('Bronze');
            });

            it('returns "Silver" for BlockSilver', function () {
                expect(AcrobaticLevel::BlockSilver->displayValue())->toBe('Silver');
            });

            it('returns "Gold" for BlockGold', function () {
                expect(AcrobaticLevel::BlockGold->displayValue())->toBe('Gold');
            });

            it('returns "Platinum" for BlockPlatinum', function () {
                expect(AcrobaticLevel::BlockPlatinum->displayValue())->toBe('Platinum');
            });

            it('returns "Diamond" for BlockDiamond', function () {
                expect(AcrobaticLevel::BlockDiamond->displayValue())->toBe('Diamond');
            });
        });
    });

    describe('isBlockLevel()', function () {
        it('returns true for BlockBronze', function () {
            expect(AcrobaticLevel::BlockBronze->isBlockLevel())->toBeTrue();
        });

        it('returns true for BlockSilver', function () {
            expect(AcrobaticLevel::BlockSilver->isBlockLevel())->toBeTrue();
        });

        it('returns true for BlockGold', function () {
            expect(AcrobaticLevel::BlockGold->isBlockLevel())->toBeTrue();
        });

        it('returns true for BlockPlatinum', function () {
            expect(AcrobaticLevel::BlockPlatinum->isBlockLevel())->toBeTrue();
        });

        it('returns true for BlockDiamond', function () {
            expect(AcrobaticLevel::BlockDiamond->isBlockLevel())->toBeTrue();
        });

        it('returns false for numeric levels', function () {
            expect(AcrobaticLevel::Level1->isBlockLevel())->toBeFalse();
            expect(AcrobaticLevel::Level5->isBlockLevel())->toBeFalse();
            expect(AcrobaticLevel::Level10->isBlockLevel())->toBeFalse();
        });

        it('returns false for elite levels', function () {
            expect(AcrobaticLevel::Elite->isBlockLevel())->toBeFalse();
            expect(AcrobaticLevel::SeniorElite->isBlockLevel())->toBeFalse();
        });
    });

    describe('isPairGroupLevel()', function () {
        it('returns true for numeric levels', function () {
            expect(AcrobaticLevel::Level1->isPairGroupLevel())->toBeTrue();
            expect(AcrobaticLevel::Level5->isPairGroupLevel())->toBeTrue();
            expect(AcrobaticLevel::Level10->isPairGroupLevel())->toBeTrue();
        });

        it('returns true for elite levels', function () {
            expect(AcrobaticLevel::Elite->isPairGroupLevel())->toBeTrue();
            expect(AcrobaticLevel::SeniorElite->isPairGroupLevel())->toBeTrue();
            expect(AcrobaticLevel::JuniorElite12To18->isPairGroupLevel())->toBeTrue();
        });

        it('returns true for Age11To16', function () {
            expect(AcrobaticLevel::Age11To16->isPairGroupLevel())->toBeTrue();
        });

        it('returns true for Exhibition', function () {
            expect(AcrobaticLevel::Exhibition->isPairGroupLevel())->toBeTrue();
        });

        it('returns false for block levels', function () {
            expect(AcrobaticLevel::BlockBronze->isPairGroupLevel())->toBeFalse();
            expect(AcrobaticLevel::BlockGold->isPairGroupLevel())->toBeFalse();
            expect(AcrobaticLevel::BlockDiamond->isPairGroupLevel())->toBeFalse();
        });
    });

    describe('isElite()', function () {
        it('returns true for Elite', function () {
            expect(AcrobaticLevel::Elite->isElite())->toBeTrue();
        });

        it('returns true for JuniorElite12To18', function () {
            expect(AcrobaticLevel::JuniorElite12To18->isElite())->toBeTrue();
        });

        it('returns true for JuniorElite13To19', function () {
            expect(AcrobaticLevel::JuniorElite13To19->isElite())->toBeTrue();
        });

        it('returns true for SeniorElite', function () {
            expect(AcrobaticLevel::SeniorElite->isElite())->toBeTrue();
        });

        it('returns false for numeric levels', function () {
            expect(AcrobaticLevel::Level1->isElite())->toBeFalse();
            expect(AcrobaticLevel::Level10->isElite())->toBeFalse();
        });

        it('returns false for block levels', function () {
            expect(AcrobaticLevel::BlockBronze->isElite())->toBeFalse();
            expect(AcrobaticLevel::BlockDiamond->isElite())->toBeFalse();
        });

        it('returns false for Age11To16', function () {
            expect(AcrobaticLevel::Age11To16->isElite())->toBeFalse();
        });

        it('returns false for Exhibition', function () {
            expect(AcrobaticLevel::Exhibition->isElite())->toBeFalse();
        });
    });

    describe('fromDisplayValue()', function () {
        it('returns correct enum for numeric levels', function () {
            expect(AcrobaticLevel::fromDisplayValue('1'))->toBe(AcrobaticLevel::Level1);
            expect(AcrobaticLevel::fromDisplayValue('5'))->toBe(AcrobaticLevel::Level5);
            expect(AcrobaticLevel::fromDisplayValue('10'))->toBe(AcrobaticLevel::Level10);
        });

        it('returns correct enum for block levels', function () {
            expect(AcrobaticLevel::fromDisplayValue('Bronze'))->toBe(AcrobaticLevel::BlockBronze);
            expect(AcrobaticLevel::fromDisplayValue('Silver'))->toBe(AcrobaticLevel::BlockSilver);
            expect(AcrobaticLevel::fromDisplayValue('Gold'))->toBe(AcrobaticLevel::BlockGold);
            expect(AcrobaticLevel::fromDisplayValue('Platinum'))->toBe(AcrobaticLevel::BlockPlatinum);
            expect(AcrobaticLevel::fromDisplayValue('Diamond'))->toBe(AcrobaticLevel::BlockDiamond);
        });

        it('returns correct enum for case-insensitive match', function () {
            expect(AcrobaticLevel::fromDisplayValue('bronze'))->toBe(AcrobaticLevel::BlockBronze);
            expect(AcrobaticLevel::fromDisplayValue('BRONZE'))->toBe(AcrobaticLevel::BlockBronze);
        });

        it('returns correct enum for elite levels', function () {
            expect(AcrobaticLevel::fromDisplayValue('Elite'))->toBe(AcrobaticLevel::Elite);
            expect(AcrobaticLevel::fromDisplayValue('SElite'))->toBe(AcrobaticLevel::SeniorElite);
            expect(AcrobaticLevel::fromDisplayValue('JElite12-18'))->toBe(AcrobaticLevel::JuniorElite12To18);
            expect(AcrobaticLevel::fromDisplayValue('JElite13-19'))->toBe(AcrobaticLevel::JuniorElite13To19);
        });

        it('returns correct enum for age group level', function () {
            expect(AcrobaticLevel::fromDisplayValue('11-16'))->toBe(AcrobaticLevel::Age11To16);
        });

        it('returns correct enum for Exhibition', function () {
            expect(AcrobaticLevel::fromDisplayValue('Exhib'))->toBe(AcrobaticLevel::Exhibition);
        });

        it('returns null for non-existent value', function () {
            expect(AcrobaticLevel::fromDisplayValue('NonExistent'))->toBeNull();
        });

        it('returns null for empty string', function () {
            expect(AcrobaticLevel::fromDisplayValue(''))->toBeNull();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(AcrobaticLevel::tryFrom('SLEVEL01'))->toBe(AcrobaticLevel::Level1);
            expect(AcrobaticLevel::tryFrom('SGBRONZE'))->toBe(AcrobaticLevel::BlockBronze);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(AcrobaticLevel::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(AcrobaticLevel::from('SELITE'))->toBe(AcrobaticLevel::Elite);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => AcrobaticLevel::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('level categorization consistency', function () {
        it('counts exactly 5 block levels', function () {
            $blockLevels = array_filter(
                AcrobaticLevel::cases(),
                fn (AcrobaticLevel $level) => $level->isBlockLevel()
            );
            expect($blockLevels)->toHaveCount(5);
        });

        it('counts exactly 16 pair/group levels', function () {
            $pairGroupLevels = array_filter(
                AcrobaticLevel::cases(),
                fn (AcrobaticLevel $level) => $level->isPairGroupLevel()
            );
            expect($pairGroupLevels)->toHaveCount(16);
        });

        it('counts exactly 4 elite levels', function () {
            $eliteLevels = array_filter(
                AcrobaticLevel::cases(),
                fn (AcrobaticLevel $level) => $level->isElite()
            );
            expect($eliteLevels)->toHaveCount(4);
        });

        it('every level is either block or pair/group', function () {
            foreach (AcrobaticLevel::cases() as $level) {
                expect($level->isBlockLevel() xor $level->isPairGroupLevel())->toBeTrue();
            }
        });

        it('block and pair/group are mutually exclusive', function () {
            foreach (AcrobaticLevel::cases() as $level) {
                expect($level->isBlockLevel() && $level->isPairGroupLevel())->toBeFalse();
            }
        });

        it('elite levels are all pair/group levels', function () {
            foreach (AcrobaticLevel::cases() as $level) {
                if ($level->isElite()) {
                    expect($level->isPairGroupLevel())->toBeTrue();
                }
            }
        });
    });
});
