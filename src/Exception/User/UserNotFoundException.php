<?php
namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    public function __construct(
        string $message = 'Usuario no encontrado.',
        ?\Throwable $previous = null,
        int $code = 404)
    {
        parent::__construct($message, $previous, $code);
    }
}
