<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Data;

use AustinW\UsaGym\Enums\Discipline;
use AustinW\UsaGym\Enums\MemberType;
use AustinW\UsaGym\Enums\MemberStatus;
use DateTimeImmutable;

/**
 * Judge reservation data from the API
 */
final readonly class JudgeReservation
{
    /**
     * @param array<string> $certifications
     */
    public function __construct(
        public string $memberId,
        public string $lastName,
        public string $firstName,
        public Discipline $discipline,
        public MemberType $memberType,
        public bool $internationalMember,
        public MemberStatus $status,
        public ?DateTimeImmutable $registrationDate,
        public string $level,
        public bool $scratched,
        public ?DateTimeImmutable $scratchDate,
        public ?DateTimeImmutable $modifiedDate,
        public array $certifications,
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
            discipline: Discipline::fromApi($data['Discipline']),
            memberType: MemberType::from($data['MemberType']),
            internationalMember: (bool) ($data['InternationalMember'] ?? false),
            status: MemberStatus::from($data['Status']),
            registrationDate: self::parseDateTime($data['RegDate'] ?? null),
            level: $data['Level'] ?? 'Judge',
            scratched: (bool) ($data['Scratched'] ?? false),
            scratchDate: self::parseDateTime($data['ScratchDate'] ?? null),
            modifiedDate: self::parseDateTime($data['ModifiedDate'] ?? null),
            certifications: $data['Certification'] ?? [],
        );
    }

    /**
     * Get the full name of the judge
     */
    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * Check if the judge has a specific certification
     */
    public function hasCertification(string $code): bool
    {
        return in_array($code, $this->certifications, true);
    }

    private static function parseDateTime(?string $value): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return new DateTimeImmutable($value);
        } catch (\Exception) {
            return null;
        }
    }
}
