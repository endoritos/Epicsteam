<?php

namespace App\Entity;

use App\Repository\FriendshipRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendshipRequestRepository::class)]
class FriendshipRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $RequestId = null;

    #[ORM\Column]
    private ?int $senderId = null;

    #[ORM\Column]
    private ?int $receiverId = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $requestedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestId(): ?int
    {
        return $this->RequestId;
    }

    public function setRequestId(int $RequestId): static
    {
        $this->RequestId = $RequestId;

        return $this;
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): static
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): static
    {
        $this->receiverId = $receiverId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRequestedAt(): ?\DateTimeInterface
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeInterface $requestedAt): static
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }
}
