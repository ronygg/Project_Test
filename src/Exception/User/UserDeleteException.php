<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserDeleteException extends HttpException
{
    public function __construct(
        string $message = 'Error al eliminar el usuario',
        int $statusCode = 500,
        ?\Throwable $previous = null
        )
    {
        parent::__construct($statusCode,$message, $previous);
    }
}
