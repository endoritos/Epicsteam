<?php

namespace App\Entity;

use App\Repository\MassagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MassagesRepository::class)]
class Massages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $MassageId = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $Seen = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBlocked = null;

    #[ORM\Column]
    private ?int $reserverId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMassageId(): ?int
    {
        return $this->MassageId;
    }

    public function setMassageId(int $MassageId): static
    {
        $this->MassageId = $MassageId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isSeen(): ?bool
    {
        return $this->Seen;
    }

    public function setSeen(bool $Seen): static
    {
        $this->Seen = $Seen;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(?bool $isBlocked): static
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getReserverId(): ?int
    {
        return $this->reserverId;
    }

    public function setReserverId(int $reserverId): static
    {
        $this->reserverId = $reserverId;

        return $this;
    }
}
