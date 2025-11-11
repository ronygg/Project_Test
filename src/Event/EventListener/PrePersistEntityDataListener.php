<?php

namespace App\Event\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
readonly class PrePersistEntityDataListener
{
    public function __construct(private Security $security)
    {}

    public function prePersist(PrePersistEventArgs $args) {
        $this->setUserAndDateValues(true, $args->getObject());
    }

    public function preUpdate(PreUpdateEventArgs $args) {
        $this->setUserAndDateValues(false, $args->getObject());
    }

    private function setUserAndDateValues(bool $prePersist, mixed $entity): void
    {
        $currentDate = new \DateTime();
        $user = $this->security->getUser();

        if ($prePersist) {
            $entity->setCreateDate($currentDate);
            $entity->setCreateBy($user);
        } else {
            $entity->setUpdateDate($currentDate);
            $entity->setUpdateBy($user);
        }
    }

}
