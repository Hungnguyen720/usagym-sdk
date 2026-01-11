<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\MemberType;

describe('MemberType', function () {
    describe('enum cases', function () {
        it('has exactly 10 cases', function () {
            expect(MemberType::cases())->toHaveCount(10);
        });

        it('has Athlete case with correct value', function () {
            expect(MemberType::Athlete->value)->toBe('ATHL');
        });

        it('has CompetitiveCoach case with correct value', function () {
            expect(MemberType::CompetitiveCoach->value)->toBe('CCOACH');
        });

        it('has Judge case with correct value', function () {
            expect(MemberType::Judge->value)->toBe('JUDGE');
        });

        it('has Instructor case with correct value (deprecated)', function () {
            expect(MemberType::Instructor->value)->toBe('INST');
        });

        it('has JuniorProfessional case with correct value (deprecated)', function () {
            expect(MemberType::JuniorProfessional->value)->toBe('JPRO');
        });

        it('has Professional case with correct value (deprecated)', function () {
            expect(MemberType::Professional->value)->toBe('PRO');
        });

        it('has InternationalCoach case with correct value (deprecated)', function () {
            expect(MemberType::InternationalCoach->value)->toBe('IFCO');
        });

        it('has InternationalJudge case with correct value (deprecated)', function () {
            expect(MemberType::InternationalJudge->value)->toBe('IFJO');
        });

        it('has InternationalAthlete case with correct value (deprecated)', function () {
            expect(MemberType::InternationalAthlete->value)->toBe('IFAT');
        });

        it('has InternationalTrainee case with correct value (deprecated)', function () {
            expect(MemberType::InternationalTrainee->value)->toBe('INTR');
        });
    });

    describe('label()', function () {
        it('returns "Athlete" for Athlete', function () {
            expect(MemberType::Athlete->label())->toBe('Athlete');
        });

        it('returns "Competitive Coach" for CompetitiveCoach', function () {
            expect(MemberType::CompetitiveCoach->label())->toBe('Competitive Coach');
        });

        it('returns "Judge" for Judge', function () {
            expect(MemberType::Judge->label())->toBe('Judge');
        });

        it('returns "Instructor (Deprecated)" for Instructor', function () {
            expect(MemberType::Instructor->label())->toBe('Instructor (Deprecated)');
        });

        it('returns "Junior Professional (Deprecated)" for JuniorProfessional', function () {
            expect(MemberType::JuniorProfessional->label())->toBe('Junior Professional (Deprecated)');
        });

        it('returns "Professional (Deprecated)" for Professional', function () {
            expect(MemberType::Professional->label())->toBe('Professional (Deprecated)');
        });

        it('returns "International Coach (Deprecated)" for InternationalCoach', function () {
            expect(MemberType::InternationalCoach->label())->toBe('International Coach (Deprecated)');
        });

        it('returns "International Judge (Deprecated)" for InternationalJudge', function () {
            expect(MemberType::InternationalJudge->label())->toBe('International Judge (Deprecated)');
        });

        it('returns "International Athlete (Deprecated)" for InternationalAthlete', function () {
            expect(MemberType::InternationalAthlete->label())->toBe('International Athlete (Deprecated)');
        });

        it('returns "International Trainee (Deprecated)" for InternationalTrainee', function () {
            expect(MemberType::InternationalTrainee->label())->toBe('International Trainee (Deprecated)');
        });
    });

    describe('isDeprecated()', function () {
        it('returns false for Athlete', function () {
            expect(MemberType::Athlete->isDeprecated())->toBeFalse();
        });

        it('returns false for CompetitiveCoach', function () {
            expect(MemberType::CompetitiveCoach->isDeprecated())->toBeFalse();
        });

        it('returns false for Judge', function () {
            expect(MemberType::Judge->isDeprecated())->toBeFalse();
        });

        it('returns true for Instructor', function () {
            expect(MemberType::Instructor->isDeprecated())->toBeTrue();
        });

        it('returns true for JuniorProfessional', function () {
            expect(MemberType::JuniorProfessional->isDeprecated())->toBeTrue();
        });

        it('returns true for Professional', function () {
            expect(MemberType::Professional->isDeprecated())->toBeTrue();
        });

        it('returns true for InternationalCoach', function () {
            expect(MemberType::InternationalCoach->isDeprecated())->toBeTrue();
        });

        it('returns true for InternationalJudge', function () {
            expect(MemberType::InternationalJudge->isDeprecated())->toBeTrue();
        });

        it('returns true for InternationalAthlete', function () {
            expect(MemberType::InternationalAthlete->isDeprecated())->toBeTrue();
        });

        it('returns true for InternationalTrainee', function () {
            expect(MemberType::InternationalTrainee->isDeprecated())->toBeTrue();
        });
    });

    describe('isAthlete()', function () {
        it('returns true for Athlete', function () {
            expect(MemberType::Athlete->isAthlete())->toBeTrue();
        });

        it('returns true for InternationalAthlete', function () {
            expect(MemberType::InternationalAthlete->isAthlete())->toBeTrue();
        });

        it('returns true for InternationalTrainee', function () {
            expect(MemberType::InternationalTrainee->isAthlete())->toBeTrue();
        });

        it('returns false for CompetitiveCoach', function () {
            expect(MemberType::CompetitiveCoach->isAthlete())->toBeFalse();
        });

        it('returns false for Judge', function () {
            expect(MemberType::Judge->isAthlete())->toBeFalse();
        });

        it('returns false for Instructor', function () {
            expect(MemberType::Instructor->isAthlete())->toBeFalse();
        });

        it('returns false for Professional', function () {
            expect(MemberType::Professional->isAthlete())->toBeFalse();
        });
    });

    describe('isCoach()', function () {
        it('returns true for CompetitiveCoach', function () {
            expect(MemberType::CompetitiveCoach->isCoach())->toBeTrue();
        });

        it('returns true for Instructor', function () {
            expect(MemberType::Instructor->isCoach())->toBeTrue();
        });

        it('returns true for InternationalCoach', function () {
            expect(MemberType::InternationalCoach->isCoach())->toBeTrue();
        });

        it('returns false for Athlete', function () {
            expect(MemberType::Athlete->isCoach())->toBeFalse();
        });

        it('returns false for Judge', function () {
            expect(MemberType::Judge->isCoach())->toBeFalse();
        });

        it('returns false for Professional', function () {
            expect(MemberType::Professional->isCoach())->toBeFalse();
        });

        it('returns false for InternationalAthlete', function () {
            expect(MemberType::InternationalAthlete->isCoach())->toBeFalse();
        });
    });

    describe('isJudge()', function () {
        it('returns true for Judge', function () {
            expect(MemberType::Judge->isJudge())->toBeTrue();
        });

        it('returns true for Professional', function () {
            expect(MemberType::Professional->isJudge())->toBeTrue();
        });

        it('returns true for JuniorProfessional', function () {
            expect(MemberType::JuniorProfessional->isJudge())->toBeTrue();
        });

        it('returns true for InternationalJudge', function () {
            expect(MemberType::InternationalJudge->isJudge())->toBeTrue();
        });

        it('returns false for Athlete', function () {
            expect(MemberType::Athlete->isJudge())->toBeFalse();
        });

        it('returns false for CompetitiveCoach', function () {
            expect(MemberType::CompetitiveCoach->isJudge())->toBeFalse();
        });

        it('returns false for Instructor', function () {
            expect(MemberType::Instructor->isJudge())->toBeFalse();
        });

        it('returns false for InternationalCoach', function () {
            expect(MemberType::InternationalCoach->isJudge())->toBeFalse();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(MemberType::tryFrom('ATHL'))->toBe(MemberType::Athlete);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(MemberType::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(MemberType::from('CCOACH'))->toBe(MemberType::CompetitiveCoach);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => MemberType::from('INVALID'))->toThrow(ValueError::class);
        });
    });

    describe('role categorization consistency', function () {
        it('ensures active member types have exactly 3 cases', function () {
            $activeTypes = array_filter(
                MemberType::cases(),
                fn (MemberType $type) => !$type->isDeprecated()
            );
            expect($activeTypes)->toHaveCount(3);
        });

        it('ensures deprecated member types have exactly 7 cases', function () {
            $deprecatedTypes = array_filter(
                MemberType::cases(),
                fn (MemberType $type) => $type->isDeprecated()
            );
            expect($deprecatedTypes)->toHaveCount(7);
        });

        it('counts exactly 3 athlete types', function () {
            $athleteTypes = array_filter(
                MemberType::cases(),
                fn (MemberType $type) => $type->isAthlete()
            );
            expect($athleteTypes)->toHaveCount(3);
        });

        it('counts exactly 3 coach types', function () {
            $coachTypes = array_filter(
                MemberType::cases(),
                fn (MemberType $type) => $type->isCoach()
            );
            expect($coachTypes)->toHaveCount(3);
        });

        it('counts exactly 4 judge types', function () {
            $judgeTypes = array_filter(
                MemberType::cases(),
                fn (MemberType $type) => $type->isJudge()
            );
            expect($judgeTypes)->toHaveCount(4);
        });
    });
});
