<?php

declare(strict_types=1);

use AustinW\UsaGym\Data\GroupAthlete;

describe('GroupAthlete', function () {
    describe('fromArray', function () {
        it('creates from array with all fields', function () {
            $data = [
                'MemberID' => '111111',
                'LastName' => 'Anderson',
                'FirstName' => 'Alice',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete)->toBeInstanceOf(GroupAthlete::class);
            expect($athlete->memberId)->toBe('111111');
            expect($athlete->lastName)->toBe('Anderson');
            expect($athlete->firstName)->toBe('Alice');
        });

        it('creates from fixture group athletes', function () {
            $data = loadFixture('group.json');
            $athleteData = $data['Athletes'][0];
            $athlete = GroupAthlete::fromArray($athleteData);

            expect($athlete->memberId)->toBe('111111');
            expect($athlete->lastName)->toBe('Anderson');
            expect($athlete->firstName)->toBe('Alice');
        });

        it('handles numeric member ID', function () {
            $data = [
                'MemberID' => 222333,
                'LastName' => 'Brown',
                'FirstName' => 'Bob',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete->memberId)->toBe('222333');
        });

        it('creates all athletes from fixture', function () {
            $data = loadFixture('group.json');
            $athletes = array_map(
                fn(array $athleteData) => GroupAthlete::fromArray($athleteData),
                $data['Athletes']
            );

            expect($athletes)->toHaveCount(5);

            $expectedNames = [
                'Alice Anderson',
                'Betty Brown',
                'Carol Clark',
                'Diana Davis',
                'Eve Evans',
            ];

            foreach ($athletes as $index => $athlete) {
                expect($athlete->fullName())->toBe($expectedNames[$index]);
            }
        });
    });

    describe('fullName', function () {
        it('returns full name', function () {
            $data = [
                'MemberID' => '111111',
                'LastName' => 'Anderson',
                'FirstName' => 'Alice',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete->fullName())->toBe('Alice Anderson');
        });

        it('returns full name with different names', function () {
            $data = [
                'MemberID' => '222222',
                'LastName' => 'Smith-Jones',
                'FirstName' => 'Mary Ann',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete->fullName())->toBe('Mary Ann Smith-Jones');
        });

        it('handles single character names', function () {
            $data = [
                'MemberID' => '333333',
                'LastName' => 'X',
                'FirstName' => 'Y',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete->fullName())->toBe('Y X');
        });

        it('returns full name for each fixture athlete', function () {
            $data = loadFixture('group.json');

            $expectedFullNames = [
                'Alice Anderson',
                'Betty Brown',
                'Carol Clark',
                'Diana Davis',
                'Eve Evans',
            ];

            foreach ($data['Athletes'] as $index => $athleteData) {
                $athlete = GroupAthlete::fromArray($athleteData);
                expect($athlete->fullName())->toBe($expectedFullNames[$index]);
            }
        });
    });

    describe('data types', function () {
        it('ensures memberId is string', function () {
            $data = [
                'MemberID' => 999888,
                'LastName' => 'Test',
                'FirstName' => 'User',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete->memberId)->toBeString();
            expect($athlete->memberId)->toBe('999888');
        });

        it('preserves string memberId', function () {
            $data = [
                'MemberID' => '000123',
                'LastName' => 'Leading',
                'FirstName' => 'Zero',
            ];
            $athlete = GroupAthlete::fromArray($data);

            expect($athlete->memberId)->toBe('000123');
        });
    });

    describe('readonly properties', function () {
        it('has readonly memberId', function () {
            $reflection = new ReflectionClass(GroupAthlete::class);
            $property = $reflection->getProperty('memberId');

            expect($property->isReadOnly())->toBeTrue();
        });

        it('has readonly lastName', function () {
            $reflection = new ReflectionClass(GroupAthlete::class);
            $property = $reflection->getProperty('lastName');

            expect($property->isReadOnly())->toBeTrue();
        });

        it('has readonly firstName', function () {
            $reflection = new ReflectionClass(GroupAthlete::class);
            $property = $reflection->getProperty('firstName');

            expect($property->isReadOnly())->toBeTrue();
        });
    });
});
