<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\MemberStatus;

describe('MemberStatus', function () {
    describe('enum cases', function () {
        it('has exactly 7 cases', function () {
            expect(MemberStatus::cases())->toHaveCount(7);
        });

        it('has Active case with correct value', function () {
            expect(MemberStatus::Active->value)->toBe('Active');
        });

        it('has Pending case with correct value', function () {
            expect(MemberStatus::Pending->value)->toBe('Pending');
        });

        it('has Expired case with correct value', function () {
            expect(MemberStatus::Expired->value)->toBe('Expired');
        });

        it('has Banned case with correct value', function () {
            expect(MemberStatus::Banned->value)->toBe('Banned');
        });

        it('has Suspended case with correct value', function () {
            expect(MemberStatus::Suspended->value)->toBe('Suspended');
        });

        it('has Terminated case with correct value', function () {
            expect(MemberStatus::Terminated->value)->toBe('Terminated');
        });

        it('has Approval case with correct value', function () {
            expect(MemberStatus::Approval->value)->toBe('Approval');
        });
    });

    describe('isGoodStanding()', function () {
        it('returns true for Active', function () {
            expect(MemberStatus::Active->isGoodStanding())->toBeTrue();
        });

        it('returns false for Pending', function () {
            expect(MemberStatus::Pending->isGoodStanding())->toBeFalse();
        });

        it('returns false for Expired', function () {
            expect(MemberStatus::Expired->isGoodStanding())->toBeFalse();
        });

        it('returns false for Banned', function () {
            expect(MemberStatus::Banned->isGoodStanding())->toBeFalse();
        });

        it('returns false for Suspended', function () {
            expect(MemberStatus::Suspended->isGoodStanding())->toBeFalse();
        });

        it('returns false for Terminated', function () {
            expect(MemberStatus::Terminated->isGoodStanding())->toBeFalse();
        });

        it('returns false for Approval', function () {
            expect(MemberStatus::Approval->isGoodStanding())->toBeFalse();
        });
    });

    describe('canParticipate()', function () {
        it('returns true for Active', function () {
            expect(MemberStatus::Active->canParticipate())->toBeTrue();
        });

        it('returns true for Pending', function () {
            expect(MemberStatus::Pending->canParticipate())->toBeTrue();
        });

        it('returns false for Expired', function () {
            expect(MemberStatus::Expired->canParticipate())->toBeFalse();
        });

        it('returns false for Banned', function () {
            expect(MemberStatus::Banned->canParticipate())->toBeFalse();
        });

        it('returns false for Suspended', function () {
            expect(MemberStatus::Suspended->canParticipate())->toBeFalse();
        });

        it('returns false for Terminated', function () {
            expect(MemberStatus::Terminated->canParticipate())->toBeFalse();
        });

        it('returns false for Approval', function () {
            expect(MemberStatus::Approval->canParticipate())->toBeFalse();
        });
    });

    describe('isProblem()', function () {
        it('returns true for Banned', function () {
            expect(MemberStatus::Banned->isProblem())->toBeTrue();
        });

        it('returns true for Suspended', function () {
            expect(MemberStatus::Suspended->isProblem())->toBeTrue();
        });

        it('returns true for Terminated', function () {
            expect(MemberStatus::Terminated->isProblem())->toBeTrue();
        });

        it('returns false for Active', function () {
            expect(MemberStatus::Active->isProblem())->toBeFalse();
        });

        it('returns false for Pending', function () {
            expect(MemberStatus::Pending->isProblem())->toBeFalse();
        });

        it('returns false for Expired', function () {
            expect(MemberStatus::Expired->isProblem())->toBeFalse();
        });

        it('returns false for Approval', function () {
            expect(MemberStatus::Approval->isProblem())->toBeFalse();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(MemberStatus::tryFrom('Active'))->toBe(MemberStatus::Active);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(MemberStatus::tryFrom('Invalid'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(MemberStatus::from('Pending'))->toBe(MemberStatus::Pending);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => MemberStatus::from('Invalid'))->toThrow(ValueError::class);
        });

        it('is case-sensitive for value matching', function () {
            expect(MemberStatus::tryFrom('active'))->toBeNull();
            expect(MemberStatus::tryFrom('ACTIVE'))->toBeNull();
        });
    });

    describe('status categorization consistency', function () {
        it('only Active is in good standing', function () {
            $goodStandingStatuses = array_filter(
                MemberStatus::cases(),
                fn (MemberStatus $status) => $status->isGoodStanding()
            );
            expect($goodStandingStatuses)->toHaveCount(1);
            expect(array_values($goodStandingStatuses)[0])->toBe(MemberStatus::Active);
        });

        it('counts exactly 2 statuses that can participate', function () {
            $participatingStatuses = array_filter(
                MemberStatus::cases(),
                fn (MemberStatus $status) => $status->canParticipate()
            );
            expect($participatingStatuses)->toHaveCount(2);
        });

        it('counts exactly 3 problem statuses', function () {
            $problemStatuses = array_filter(
                MemberStatus::cases(),
                fn (MemberStatus $status) => $status->isProblem()
            );
            expect($problemStatuses)->toHaveCount(3);
        });

        it('problem statuses cannot participate', function () {
            foreach (MemberStatus::cases() as $status) {
                if ($status->isProblem()) {
                    expect($status->canParticipate())->toBeFalse();
                }
            }
        });

        it('good standing status can participate', function () {
            foreach (MemberStatus::cases() as $status) {
                if ($status->isGoodStanding()) {
                    expect($status->canParticipate())->toBeTrue();
                }
            }
        });
    });
});
