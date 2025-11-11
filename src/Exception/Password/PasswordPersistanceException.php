<?php

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PasswordPersistanceException extends HttpException
{
    public function __construct(
        string $message,
        int $statusCode = 500,
        ?\Throwable $previous = null
    )
    {
        parent::__construct($message, $statusCode, $previous);

    }
}
