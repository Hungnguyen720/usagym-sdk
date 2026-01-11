<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\ReservationType;

describe('ReservationType', function () {
    describe('enum cases', function () {
        it('has exactly 6 cases', function () {
            expect(ReservationType::cases())->toHaveCount(6);
        });

        it('has Athlete case with correct value', function () {
            expect(ReservationType::Athlete->value)->toBe('athlete');
        });

        it('has Coach case with correct value', function () {
            expect(ReservationType::Coach->value)->toBe('coach');
        });

        it('has Judge case with correct value', function () {
            expect(ReservationType::Judge->value)->toBe('judge');
        });

        it('has Individual case with correct value', function () {
            expect(ReservationType::Individual->value)->toBe('individual');
        });

        it('has Group case with correct value', function () {
            expect(ReservationType::Group->value)->toBe('group');
        });

        it('has Club case with correct value', function () {
            expect(ReservationType::Club->value)->toBe('club');
        });
    });

    describe('endpoint()', function () {
        it('returns "athlete" for Athlete', function () {
            expect(ReservationType::Athlete->endpoint())->toBe('athlete');
        });

        it('returns "coach" for Coach', function () {
            expect(ReservationType::Coach->endpoint())->toBe('coach');
        });

        it('returns "judge" for Judge', function () {
            expect(ReservationType::Judge->endpoint())->toBe('judge');
        });

        it('returns "individual" for Individual', function () {
            expect(ReservationType::Individual->endpoint())->toBe('individual');
        });

        it('returns "group" for Group', function () {
            expect(ReservationType::Group->endpoint())->toBe('group');
        });

        it('returns "club" for Club', function () {
            expect(ReservationType::Club->endpoint())->toBe('club');
        });

        it('endpoint matches the enum value for all cases', function () {
            foreach (ReservationType::cases() as $case) {
                expect($case->endpoint())->toBe($case->value);
            }
        });
    });

    describe('isIndividualType()', function () {
        it('returns true for Athlete', function () {
            expect(ReservationType::Athlete->isIndividualType())->toBeTrue();
        });

        it('returns true for Coach', function () {
            expect(ReservationType::Coach->isIndividualType())->toBeTrue();
        });

        it('returns true for Judge', function () {
            expect(ReservationType::Judge->isIndividualType())->toBeTrue();
        });

        it('returns true for Individual', function () {
            expect(ReservationType::Individual->isIndividualType())->toBeTrue();
        });

        it('returns false for Group', function () {
            expect(ReservationType::Group->isIndividualType())->toBeFalse();
        });

        it('returns false for Club', function () {
            expect(ReservationType::Club->isIndividualType())->toBeFalse();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(ReservationType::tryFrom('athlete'))->toBe(ReservationType::Athlete);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(ReservationType::tryFrom('invalid'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(ReservationType::from('coach'))->toBe(ReservationType::Coach);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => ReservationType::from('invalid'))->toThrow(ValueError::class);
        });

        it('is case-sensitive for value matching', function () {
            expect(ReservationType::tryFrom('Athlete'))->toBeNull();
            expect(ReservationType::tryFrom('ATHLETE'))->toBeNull();
        });
    });

    describe('type categorization consistency', function () {
        it('counts exactly 4 individual types', function () {
            $individualTypes = array_filter(
                ReservationType::cases(),
                fn (ReservationType $type) => $type->isIndividualType()
            );
            expect($individualTypes)->toHaveCount(4);
        });

        it('counts exactly 2 non-individual types', function () {
            $nonIndividualTypes = array_filter(
                ReservationType::cases(),
                fn (ReservationType $type) => !$type->isIndividualType()
            );
            expect($nonIndividualTypes)->toHaveCount(2);
        });

        it('Group and Club are the only non-individual types', function () {
            $nonIndividualTypes = array_filter(
                ReservationType::cases(),
                fn (ReservationType $type) => !$type->isIndividualType()
            );
            $nonIndividualValues = array_map(
                fn (ReservationType $type) => $type->value,
                array_values($nonIndividualTypes)
            );
            expect($nonIndividualValues)->toContain('group');
            expect($nonIndividualValues)->toContain('club');
        });
    });
});
