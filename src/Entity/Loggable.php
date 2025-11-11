<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\MappedSuperclass]
class Loggable
{
    #[ORM\ManyToOne]
    #[Ignore]
    private ?User $createBy = null;


    #[ORM\ManyToOne]
    #[Ignore]
    private ?User $updateBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $createDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updateDate = null;

    public function __construct(
        \DateTimeInterface $createDate = null,
        \DateTimeInterface $updateDate = null,
        User $createdBy = null,
        User $updatedBy = null
    ) {
        $this->createDate = $createDate;
        $this->updateDate = $updateDate;
        $this->createBy = $createdBy;
        $this->updateBy = $updatedBy;
    }

    public function getCreateBy(): ?User
    {
        return $this->createBy;
    }

    public function setCreateBy(?User $createBy): self
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getUpdateBy(): ?User
    {
        return $this->updateBy;
    }

    public function setUpdateBy(?User $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    public function getCreateDate(): ?\DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTime $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTime
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTime $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }
}
