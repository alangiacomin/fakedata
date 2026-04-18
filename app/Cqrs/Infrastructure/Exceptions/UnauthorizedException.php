<?php

namespace App\Cqrs\Infrastructure\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function getStatusCode(): int
    {
        return 401;
    }
}
