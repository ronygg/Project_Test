<?php

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordNotFoundException extends NotFoundHttpException
{
    public function __construct(
        string $message = 'Contraseña no encontrada.',
        ?\Throwable $previous = null,
        int $code = 404)
    {
        parent::__construct($message, $previous, $code);
    }
}
