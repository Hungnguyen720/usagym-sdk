<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Data;

/**
 * Athlete data within a group reservation
 */
final readonly class GroupAthlete
{
    public function __construct(
        public string $memberId,
        public string $lastName,
        public string $firstName,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            memberId: (string) $data['MemberID'],
            lastName: $data['LastName'],
            firstName: $data['FirstName'],
        );
    }

    /**
     * Get the full name of the athlete
     */
    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
