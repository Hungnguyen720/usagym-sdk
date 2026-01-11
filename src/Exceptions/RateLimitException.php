<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Exceptions;

use Saloon\Http\Response;
use Throwable;

/**
 * Exception thrown when API rate limit is exceeded (429 responses)
 */
class RateLimitException extends UsaGymException
{
    protected readonly ?int $retryAfter;

    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(
        string $message,
        ?Response $response = null,
        ?array $data = null,
        int $code = 429,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $response, $data, $code, $previous);

        // Extract retry-after header if present
        $this->retryAfter = $response?->header('Retry-After')
            ? (int) $response->header('Retry-After')
            : null;
    }

    /**
     * Get the number of seconds to wait before retrying
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
