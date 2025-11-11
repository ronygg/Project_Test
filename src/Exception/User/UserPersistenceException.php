<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPersistenceException extends HttpException
{
    public function __construct(
        string $message = 'Error al guardar el usuario',
        int $statusCode = 500,
        ?\Throwable $previous = null
        )
    {
        parent::__construct($statusCode, $message, $previous);
    }
}
