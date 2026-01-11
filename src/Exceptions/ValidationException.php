<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Exceptions;

use Saloon\Http\Response;
use Throwable;

/**
 * Exception thrown when API validation fails (422 responses)
 */
class ValidationException extends UsaGymException
{
    /**
     * @var array<string, array<string>>
     */
    protected readonly array $errors;

    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(
        string $message,
        ?Response $response = null,
        ?array $data = null,
        int $code = 422,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $response, $data, $code, $previous);

        // Extract errors from response data
        $this->errors = $data['errors'] ?? $data['data']['errors'] ?? [];
    }

    /**
     * Get the validation errors
     *
     * @return array<string, array<string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Check if a specific field has errors
     */
    public function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Get errors for a specific field
     *
     * @return array<string>
     */
    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }
}
