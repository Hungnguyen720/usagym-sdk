<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Exceptions;

use Saloon\Http\Response;
use Throwable;

/**
 * Exception thrown when a requested resource is not found (404 responses)
 */
class NotFoundException extends UsaGymException
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(
        string $message = 'The resource you are looking for could not be found.',
        ?Response $response = null,
        ?array $data = null,
        int $code = 404,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $response, $data, $code, $previous);
    }
}
