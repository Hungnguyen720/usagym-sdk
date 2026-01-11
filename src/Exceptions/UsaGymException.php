<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Exceptions;

use Exception;
use Saloon\Http\Response;
use Throwable;

class UsaGymException extends Exception
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(
        string $message,
        protected readonly ?Response $response = null,
        protected readonly ?array $data = null,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the Saloon response if available
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Get the parsed response data if available
     *
     * @return array<string, mixed>|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Create an exception from a Saloon response
     */
    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        $message = $data['message'] ?? 'An API error occurred';
        $status = $response->status();

        return match ($status) {
            401, 403 => new AuthenticationException($message, $response, $data, $status),
            404 => new NotFoundException($message, $response, $data, $status),
            422 => new ValidationException($message, $response, $data, $status),
            429 => new RateLimitException($message, $response, $data, $status),
            default => new ApiException($message, $response, $data, $status),
        };
    }
}
