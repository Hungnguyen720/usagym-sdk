<?php

declare(strict_types=1);

use AustinW\UsaGym\Enums\Discipline;
use AustinW\UsaGym\Enums\Levels\WomensArtisticLevel;
use AustinW\UsaGym\Enums\Levels\MensArtisticLevel;
use AustinW\UsaGym\Enums\Levels\RhythmicLevel;
use AustinW\UsaGym\Enums\Levels\AcrobaticLevel;
use AustinW\UsaGym\Enums\Levels\TrampolineLevel;
use AustinW\UsaGym\Enums\Levels\GfaLevel;

describe('Discipline', function () {
    describe('enum cases', function () {
        it('has exactly 6 cases', function () {
            expect(Discipline::cases())->toHaveCount(6);
        });

        it('has WomensArtistic case with correct value', function () {
            expect(Discipline::WomensArtistic->value)->toBe('WAG');
        });

        it('has MensArtistic case with correct value', function () {
            expect(Discipline::MensArtistic->value)->toBe('MAG');
        });

        it('has Rhythmic case with correct value', function () {
            expect(Discipline::Rhythmic->value)->toBe('RG');
        });

        it('has Acrobatic case with correct value', function () {
            expect(Discipline::Acrobatic->value)->toBe('ACRO');
        });

        it('has Trampoline case with correct value', function () {
            expect(Discipline::Trampoline->value)->toBe('TRA');
        });

        it('has GymnasticsForAll case with correct value', function () {
            expect(Discipline::GymnasticsForAll->value)->toBe('GFA');
        });
    });

    describe('name()', function () {
        it('returns "Women" for WomensArtistic', function () {
            expect(Discipline::WomensArtistic->name())->toBe('Women');
        });

        it('returns "Men" for MensArtistic', function () {
            expect(Discipline::MensArtistic->name())->toBe('Men');
        });

        it('returns "Rhythmic" for Rhythmic', function () {
            expect(Discipline::Rhythmic->name())->toBe('Rhythmic');
        });

        it('returns "Acro" for Acrobatic', function () {
            expect(Discipline::Acrobatic->name())->toBe('Acro');
        });

        it('returns "TT" for Trampoline', function () {
            expect(Discipline::Trampoline->name())->toBe('TT');
        });

        it('returns "GFA" for GymnasticsForAll', function () {
            expect(Discipline::GymnasticsForAll->name())->toBe('GFA');
        });
    });

    describe('fullName()', function () {
        it('returns "Women\'s Artistic" for WomensArtistic', function () {
            expect(Discipline::WomensArtistic->fullName())->toBe("Women's Artistic");
        });

        it('returns "Men\'s Artistic" for MensArtistic', function () {
            expect(Discipline::MensArtistic->fullName())->toBe("Men's Artistic");
        });

        it('returns "Rhythmic" for Rhythmic', function () {
            expect(Discipline::Rhythmic->fullName())->toBe('Rhythmic');
        });

        it('returns "Acrobatic" for Acrobatic', function () {
            expect(Discipline::Acrobatic->fullName())->toBe('Acrobatic');
        });

        it('returns "Trampoline and Tumbling" for Trampoline', function () {
            expect(Discipline::Trampoline->fullName())->toBe('Trampoline and Tumbling');
        });

        it('returns "Gymnastics for All" for GymnasticsForAll', function () {
            expect(Discipline::GymnasticsForAll->fullName())->toBe('Gymnastics for All');
        });
    });

    describe('fromApi()', function () {
        describe('direct code matching', function () {
            it('matches WAG directly', function () {
                expect(Discipline::fromApi('WAG'))->toBe(Discipline::WomensArtistic);
            });

            it('matches MAG directly', function () {
                expect(Discipline::fromApi('MAG'))->toBe(Discipline::MensArtistic);
            });

            it('matches RG directly', function () {
                expect(Discipline::fromApi('RG'))->toBe(Discipline::Rhythmic);
            });

            it('matches ACRO directly', function () {
                expect(Discipline::fromApi('ACRO'))->toBe(Discipline::Acrobatic);
            });

            it('matches TRA directly', function () {
                expect(Discipline::fromApi('TRA'))->toBe(Discipline::Trampoline);
            });

            it('matches GFA directly', function () {
                expect(Discipline::fromApi('GFA'))->toBe(Discipline::GymnasticsForAll);
            });
        });

        describe('display name matching (case insensitive)', function () {
            it('matches "women" for WomensArtistic', function () {
                expect(Discipline::fromApi('women'))->toBe(Discipline::WomensArtistic);
            });

            it('matches "Women\'s Artistic" for WomensArtistic', function () {
                expect(Discipline::fromApi("Women's Artistic"))->toBe(Discipline::WomensArtistic);
            });

            it('matches "men" for MensArtistic', function () {
                expect(Discipline::fromApi('men'))->toBe(Discipline::MensArtistic);
            });

            it('matches "Men\'s Artistic" for MensArtistic', function () {
                expect(Discipline::fromApi("Men's Artistic"))->toBe(Discipline::MensArtistic);
            });

            it('matches "rhythmic" for Rhythmic', function () {
                expect(Discipline::fromApi('rhythmic'))->toBe(Discipline::Rhythmic);
            });

            it('matches "rg" (lowercase) for Rhythmic', function () {
                expect(Discipline::fromApi('rg'))->toBe(Discipline::Rhythmic);
            });

            it('matches "acro" for Acrobatic', function () {
                expect(Discipline::fromApi('acro'))->toBe(Discipline::Acrobatic);
            });

            it('matches "acrobatic" for Acrobatic', function () {
                expect(Discipline::fromApi('acrobatic'))->toBe(Discipline::Acrobatic);
            });

            it('matches "acrobatics" for Acrobatic', function () {
                expect(Discipline::fromApi('acrobatics'))->toBe(Discipline::Acrobatic);
            });

            it('matches "tt" for Trampoline', function () {
                expect(Discipline::fromApi('tt'))->toBe(Discipline::Trampoline);
            });

            it('matches "trampoline" for Trampoline', function () {
                expect(Discipline::fromApi('trampoline'))->toBe(Discipline::Trampoline);
            });

            it('matches "trampoline and tumbling" for Trampoline', function () {
                expect(Discipline::fromApi('trampoline and tumbling'))->toBe(Discipline::Trampoline);
            });

            it('matches "tra" (lowercase) for Trampoline', function () {
                expect(Discipline::fromApi('tra'))->toBe(Discipline::Trampoline);
            });

            it('matches "gfa" (lowercase) for GymnasticsForAll', function () {
                expect(Discipline::fromApi('gfa'))->toBe(Discipline::GymnasticsForAll);
            });

            it('matches "gymnastics for all" for GymnasticsForAll', function () {
                expect(Discipline::fromApi('gymnastics for all'))->toBe(Discipline::GymnasticsForAll);
            });
        });

        describe('error handling', function () {
            it('throws ValueError for unknown discipline', function () {
                expect(fn () => Discipline::fromApi('UNKNOWN'))
                    ->toThrow(ValueError::class, 'Unknown discipline: UNKNOWN');
            });

            it('throws ValueError for empty string', function () {
                expect(fn () => Discipline::fromApi(''))
                    ->toThrow(ValueError::class, 'Unknown discipline: ');
            });

            it('throws ValueError for invalid value', function () {
                expect(fn () => Discipline::fromApi('gymnastics'))
                    ->toThrow(ValueError::class, 'Unknown discipline: gymnastics');
            });
        });
    });

    describe('levelEnumClass()', function () {
        it('returns WomensArtisticLevel class for WomensArtistic', function () {
            expect(Discipline::WomensArtistic->levelEnumClass())->toBe(WomensArtisticLevel::class);
        });

        it('returns MensArtisticLevel class for MensArtistic', function () {
            expect(Discipline::MensArtistic->levelEnumClass())->toBe(MensArtisticLevel::class);
        });

        it('returns RhythmicLevel class for Rhythmic', function () {
            expect(Discipline::Rhythmic->levelEnumClass())->toBe(RhythmicLevel::class);
        });

        it('returns AcrobaticLevel class for Acrobatic', function () {
            expect(Discipline::Acrobatic->levelEnumClass())->toBe(AcrobaticLevel::class);
        });

        it('returns TrampolineLevel class for Trampoline', function () {
            expect(Discipline::Trampoline->levelEnumClass())->toBe(TrampolineLevel::class);
        });

        it('returns GfaLevel class for GymnasticsForAll', function () {
            expect(Discipline::GymnasticsForAll->levelEnumClass())->toBe(GfaLevel::class);
        });

        it('returns a valid enum class string that can be used', function () {
            $levelClass = Discipline::WomensArtistic->levelEnumClass();
            expect(enum_exists($levelClass))->toBeTrue();
        });
    });

    describe('backed enum functionality', function () {
        it('can be created from value using tryFrom', function () {
            expect(Discipline::tryFrom('WAG'))->toBe(Discipline::WomensArtistic);
        });

        it('returns null for invalid value using tryFrom', function () {
            expect(Discipline::tryFrom('INVALID'))->toBeNull();
        });

        it('can be created from value using from', function () {
            expect(Discipline::from('MAG'))->toBe(Discipline::MensArtistic);
        });

        it('throws ValueError for invalid value using from', function () {
            expect(fn () => Discipline::from('INVALID'))->toThrow(ValueError::class);
        });
    });
});
