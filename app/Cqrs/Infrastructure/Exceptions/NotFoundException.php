<?php

namespace App\Cqrs\Infrastructure\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function getStatusCode(): int
    {
        return 404;
    }
}
