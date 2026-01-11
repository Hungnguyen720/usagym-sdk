<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Exceptions;

/**
 * Exception thrown when API authentication fails (401/403 responses)
 */
class AuthenticationException extends UsaGymException
{
}
