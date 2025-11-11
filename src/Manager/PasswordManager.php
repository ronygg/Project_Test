<?php

namespace App\Manager;

use App\Entity\User;
use App\Exception\Password\PasswordNotHashingException;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordManager
{
    public function __construct
    (
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly LoggerInterface $logger
    ) {
    }

    public function hashingPassword(User $user, string $plainPassword): string
    {
        try {
            $passHashed = $this->passwordHasher->hashPassword(
                $user,
                $plainPassword);
        } catch (PasswordNotHashingException $e) {
            $this->logger->error($e->getMessage());
        }

        return $passHashed;
    }

    public function verifyPassword(User $user, string $oldPassword): bool
    {
        $passVerified = $this->passwordHasher->isPasswordValid(
            $user,
            $oldPassword);
        if ($passVerified) {
            return true;
        }

        return false;

    }

}
