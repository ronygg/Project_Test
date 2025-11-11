<?php

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PasswordNotHashingException extends HttpException
{
    public function __construct(
        string $message = 'Error al guardar la contraseña, no se puede encripta.',
        int $code = 500,
        ?\Throwable $previous = null
        )
    {
        parent::__construct($message, $code, $previous);
    }
}
