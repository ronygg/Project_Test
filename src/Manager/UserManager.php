<?php

namespace App\Manager;

use App\Entity\User;
use App\Exception\Password\PasswordNotFoundException;
use App\Exception\User\UserDeleteException;
use App\Exception\User\UserPersistenceException;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly PasswordManager $passwordManager,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function save(mixed $entity)
    {
        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            $this->logger->error('Error persisting user: '
                . $exception->getMessage());
            throw new UserPersistenceException();
        }
    }

    public function setUserData(
        User $user,
        ?string $plainPassword): void
    {
        if ($plainPassword) {
            $user->setPassword($this->passwordManager->hashingPassword(
                $user,
                $plainPassword));
        }
        $this->save($user);
    }

    public function remove(User $entity): void
    {
        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (ForeignKeyConstraintViolationException $exception) {
            $this->entityManager->rollback();
            $this->logger->error('Error deleting user: '
                . $exception->getMessage());
            throw $exception;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Error deleting user: '
                . $e->getMessage());
            throw new UserDeleteException();
        }
    }

    public function changePassword(User $user,
        string $plainPassword,
        string $oldPassword): void
    {
        $passVerified = $this->passwordManager->verifyPassword(
            $user,
            $oldPassword);
        if ($passVerified) {
            $hashedPass = $this->passwordManager->hashingPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPass);
            $this->save($user);
        } else {
            throw new PasswordNotFoundException();
        }
    }


    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function findUser(mixed $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

}
