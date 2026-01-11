<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Levels\TrampolineLevel;

describe('TrampolineLevel', function () {
    describe('enum cases', function () {
        it('has exactly 21 cases', function () {
            expect(TrampolineLevel::cases())->toHaveCount(21);
        });

        describe('Standard levels', function () {
            it('has Level1 through Level10 with correct values', function () {
                expect(TrampolineLevel::Level1->value)->toBe('TLEVEL01');
                expect(TrampolineLevel::Level2->value)->toBe('TLEVEL02');
                expect(TrampolineLevel::Level3->value)->toBe('TLEVEL03');
                expect(TrampolineLevel::Level4->value)->toBe('TLEVEL04');
                expect(TrampolineLevel::Level5->value)->toBe('TLEVEL05');
                expect(TrampolineLevel::Level6->value)->toBe('TLEVEL06');
                expect(TrampolineLevel::Level7->value)->toBe('TLEVEL07');
                expect(TrampolineLevel::Level8->value)->toBe('TLEVEL08');
                expect(TrampolineLevel::Level9->value)->toBe('TLEVEL09');
                expect(TrampolineLevel::Level10->value)->toBe('TLEVEL10');
            });
        });

        describe('Elite levels', function () {
            it('has OpenElite case with correct value', function () {
                expect(TrampolineLevel::OpenElite->value)->toBe('TOELITE');
            });

            it('has YouthElite11To12 case with correct value', function () {
                expect(TrampolineLevel::YouthElite11To12->value)->toBe('TYELITE1112');
            });

            it('has YouthElite13To14 case with correct value', function () {
                expect(TrampolineLevel::YouthElite13To14->value)->toBe('TYELITE1314');
            });

            it('has JuniorElite case with correct value', function () {
                expect(TrampolineLevel::JuniorElite->value)->toBe('TJELITE');
            });

            it('has IntermediateElite case with correct value', function () {
                expect(TrampolineLevel::IntermediateElite->value)->toBe('TIELITE');
            });

            it('has SeniorElite case with correct value', function () {
                expect(TrampolineLevel::SeniorElite->value)->toBe('TSELITE');
            });
        });

        describe('Special levels', function () {
            it('has Exhibition case with correct value', function () {
                expect(TrampolineLevel::Exhibition->value)->toBe('TEXHIB');
            });
        });

        describe('HUGS levels', function () {
            it('has Hugs1 case with correct value', function () {
                expect(TrampolineLevel::Hugs1->value)->toBe('TTHUGS1');
            });

            it('has Hugs2 case with correct value', function () {
                expect(TrampolineLevel::Hugs2->value)->toBe('TTHUGS2');
            });

            it('has Hugs3 case with correct value', function () {
                expect(TrampolineLevel::Hugs3->value)->toBe('TTHUGS3');
            });

            it('has Hugs4 case with correct value', function () {
                expect(TrampolineLevel::Hugs4->value)->toBe('TTHUGS4');
            });
        });
    });

    describe('displayValue()', function () {
        describe('Standard level display values', function () {
            it('returns numeric values for standard levels', function () {
                expect(TrampolineLevel::Level1->displayValue())->toBe('1');
                expect(TrampolineLevel::Level2->displayValue())->toBe('2');
                expect(TrampolineLevel::Level3->displayValue())->toBe('3');
                expect(TrampolineLevel::Level4->displayValue())->toBe('4');
                expect(TrampolineLevel::Level5->displayValue())->toBe('5');
                expect(TrampolineLevel::Level6->displayValue())->toBe('6');
                expect(TrampolineLevel::Level7->displayValue())->toBe('7');
                expect(TrampolineLevel::Level8->displayValue())->toBe('8');
                expect(TrampolineLevel::Level9->displayValue())->toBe('9');
                expect(TrampolineLevel::Level10->displayValue())->toBe('10');
            });
        });

        describe('Elite level display values', function () {
            it('returns "OElite" for OpenElite', function () {
                expect(TrampolineLevel::OpenElite->displayValue())->toBe('OElite');
            });

            it('returns "YElite1112" for YouthElite11To12', function () {
                expect(TrampolineLevel::YouthElite11To12->displayValue())->toBe('YElite1112');
            });

            it('returns "YElite1314" for YouthElite13To14', function () {
                expect(TrampolineLevel::YouthElite13To14->displayValue())->toBe('YElite1314');
            });

            it('returns "JElite" for JuniorElite', function () {
                expect(TrampolineLevel::JuniorElite->displayValue())->toBe('JElite');
            });

            it('returns "IElite" for IntermediateElite', function () {
                expect(TrampolineLevel::IntermediateElite->displayValue())->toBe('IElite');
            });

            it('returns "SElite" for SeniorElite', function () {
                expect(TrampolineLevel::SeniorElite->displayValue())->toBe('SElite');
            });
        });

        describe('Special level display values', function () {
            it('returns "Exhib" for Exhibition', function () {
                expect(TrampolineLevel::Exhibition->displayValue())->toBe('Exhib');
            });
        });

        describe('HUGS level display values', function () {
            it('returns "HUGS1" for Hugs1', function () {
                expect(TrampolineLevel::Hugs1->displayValue())->toBe('HUGS1');
            });

            it('returns "HUGS2" for Hugs2', function () {
                expect(TrampolineLevel::Hugs2->displayValue())->toBe('HUGS2');
            });

            it('returns "HUGS3" for Hugs3', function () {
                expect(TrampolineLevel::Hugs3->displayValue())->toBe('HUGS3');
            });

            it('returns "HUGS4" for Hugs4', function () {
                expect(TrampolineLevel::Hugs4->displayValue())->toBe('HUGS4');
            });
        });
    });

    describe('isElite()', function () {
        it('returns true for OpenElite', function () {
            expect(TrampolineLevel::OpenElite->isElite())->toBeTrue();
        });

        it('returns true for YouthElite11To12', function () {
            expect(TrampolineLevel::YouthElite11To12->isElite())->toBeTrue();
        });

        it('returns true for YouthElite13To14', function () {
            expect(TrampolineLevel::YouthElite13To14->isElite())->toBeTrue();
        });

        it('returns true for JuniorElite', function () {
            expect(TrampolineLevel::JuniorElite->isElite())->toBeTrue();
        });

        it('returns true for IntermediateElite', function () {
            expect(TrampolineLevel::IntermediateElite->isElite())->toBeTrue();
        });

        it('returns true for SeniorElite', function () {
            expect(TrampolineLevel::SeniorElite->isElite())->toBeTrue();
        });

        it('returns false for standard levels', function () {
            expect(TrampolineLevel::Level1->isElite())->toBeFalse();
            expect(TrampolineLevel::Level5->isElite())->toBeFalse();
            expect(TrampolineLevel::Level10->isElite())->toBeFalse();
        });

        it('returns false for HUGS levels', function () {
            expect(TrampolineLevel::Hugs1->isElite())->toBeFalse();
            expect(TrampolineLevel::Hugs4->isElite())->toBeFalse();
        });

        it('returns false for Exhibition', function () {
            expect(TrampolineLevel::Exhibition->isElite())->toBeFalse();
        });
    });

    describe('isHugs()', function () {
        it('returns true for Hugs1', function () {
            expect(TrampolineLevel::Hugs1->isHugs())->toBeTrue();
        });

        it('returns true for Hugs2', function () {
            expect(TrampolineLevel::Hugs2->isHugs())->toBeTrue();
        });

        it('returns true for Hugs3', function () {
            expect(TrampolineLevel::Hugs3->isHugs())->toBeTrue();
        });

        it('returns true for Hugs4', function () {
            expect(TrampolineLevel::Hugs4->isHugs())->toBeTrue();
        });

        it('returns false for standard levels', function () {
            expect(TrampolineLevel::Level1->isHugs())->toBeFalse();
            expect(TrampolineLevel::Level10->isHugs())->toBeFalse();
        });

        it('returns false for elite levels', function () {
            expect(TrampolineLevel::OpenElite->isHugs())->toBeFalse();
            expect(TrampolineLevel::SeniorElite->isHugs())->toBeFalse();
        });

        it('returns false for Exhibition', function () {
            expect(TrampolineLevel::Exhibition->isHugs())->toBeFalse();
        });
    });

    describe('fromDisplayValue()', function () {
        it('returns correct enum for numeric levels', function () {
            expect(TrampolineLevel::fromDisplayValue('1'))->toBe(TrampolineLevel::Level1);
            expect(TrampolineLevel::fromDisplayValue('5'))->toBe(TrampolineLevel::Level5);
            expect(TrampolineLevel::fromDisplayValue('10'))->toBe(TrampolineLevel::Level10);
        });

        it('returns correct enum for elite levels', function () {
            expect(TrampolineLevel::fromDisplayValue('OElite'))->toBe(TrampolineLevel::OpenElite);
            expect(TrampolineLevel::fromDisplayValue('YElite1112'))->toBe(TrampolineLevel::YouthElite11To12);
            expect(TrampolineLevel::fromDisplayValue('YElite1314'))->toBe(TrampolineLevel::YouthElite13To14);
            expect(TrampolineLevel::fromDisplayValue('JElite'))->toBe(TrampolineLevel::JuniorElite);
            expect(TrampolineLevel::fromDisplayValue('IElite'))->toBe(TrampolineLevel::IntermediateElite);
            expect(TrampolineLevel::fromDisplayValue('SElite'))->toBe(TrampolineLevel::SeniorElite);
        });

        it('returns correct enum for HUGS levels', function () {
            expect(TrampolineLevel::fromDisplayValue('HUGS1'))->toBe(TrampolineLevel::Hugs1);
            expect(TrampolineLevel::fromDisplayValue('HUGS2'))->toBe(TrampolineLevel::Hugs2);
            expect(TrampolineLevel::fromDisplayValue('HUGS3'))->toBe(TrampolineLevel::Hugs3);
            expect(TrampolineLevel::fromDisplayValue('HUGS4'))->toBe(TrampolineLevel::Hugs4);
        });

        it('returns correct enum for case-insensitive match', function () {
            expect(TrampolineLevel::fromDisplayValue('oelite'))->toBe(TrampolineLevel::OpenElite);
            expect(TrampolineLevel::fromDisplayValue('OELITE'))->toBe(TrampolineLevel::OpenElite);
            expect(TrampolineLevel::fromDisplayValue('hugs1'))->toBe(TrampolineLevel::Hugs1);
        });

        it('returns correct enum for Exhibition', function () {
            expect(TrampolineLevel::fromDisplayValue('Exhib'))->toBe(TrampolineLevel::Exhibition);
        });

        it('returns null for non-existent value', function () {
            expect(TrampolineLevel::fromDisplayValue('NonExistent'))->toBeNull();
        });

        it('returns null for empty string', function () {
            expect(TrampolineLevel::fromDisplayValue(''))->toBeNull();
        });

        it('returns null for invalid numeric level', function () {
            expect(TrampolineLevel::fromDisplayValue('11'))->toBeNull();
            expect(TrampolineLevel::fromDisplayValue('0'))->toBeNull();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(TrampolineLevel::tryFrom('TLEVEL01'))->toBe(TrampolineLevel::Level1);
            expect(TrampolineLevel::tryFrom('TOELITE'))->toBe(TrampolineLevel::OpenElite);
            expect(TrampolineLevel::tryFrom('TTHUGS1'))->toBe(TrampolineLevel::Hugs1);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(TrampolineLevel::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(TrampolineLevel::from('TJELITE'))->toBe(TrampolineLevel::JuniorElite);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => TrampolineLevel::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('level categorization consistency', function () {
        it('counts exactly 10 standard levels', function () {
            $standardLevels = [
                TrampolineLevel::Level1,
                TrampolineLevel::Level2,
                TrampolineLevel::Level3,
                TrampolineLevel::Level4,
                TrampolineLevel::Level5,
                TrampolineLevel::Level6,
                TrampolineLevel::Level7,
                TrampolineLevel::Level8,
                TrampolineLevel::Level9,
                TrampolineLevel::Level10,
            ];
            expect($standardLevels)->toHaveCount(10);
        });

        it('counts exactly 6 elite levels', function () {
            $eliteLevels = array_filter(
                TrampolineLevel::cases(),
                fn (TrampolineLevel $level) => $level->isElite()
            );
            expect($eliteLevels)->toHaveCount(6);
        });

        it('counts exactly 4 HUGS levels', function () {
            $hugsLevels = array_filter(
                TrampolineLevel::cases(),
                fn (TrampolineLevel $level) => $level->isHugs()
            );
            expect($hugsLevels)->toHaveCount(4);
        });

        it('elite and HUGS are mutually exclusive', function () {
            foreach (TrampolineLevel::cases() as $level) {
                expect($level->isElite() && $level->isHugs())->toBeFalse();
            }
        });

        it('standard levels are neither elite nor HUGS', function () {
            $standardLevels = [
                TrampolineLevel::Level1,
                TrampolineLevel::Level2,
                TrampolineLevel::Level3,
                TrampolineLevel::Level4,
                TrampolineLevel::Level5,
                TrampolineLevel::Level6,
                TrampolineLevel::Level7,
                TrampolineLevel::Level8,
                TrampolineLevel::Level9,
                TrampolineLevel::Level10,
            ];

            foreach ($standardLevels as $level) {
                expect($level->isElite())->toBeFalse();
                expect($level->isHugs())->toBeFalse();
            }
        });

        it('Exhibition is neither elite nor HUGS', function () {
            expect(TrampolineLevel::Exhibition->isElite())->toBeFalse();
            expect(TrampolineLevel::Exhibition->isHugs())->toBeFalse();
        });
    });
});
