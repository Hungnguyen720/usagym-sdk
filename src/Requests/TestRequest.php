<?php

declare(strict_types=1);

namespace AustinW\UsaGym\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Test API credentials
 *
 * @see https://api.usagym.org/v4/test
 */
class TestRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}
