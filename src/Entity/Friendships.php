<?php

namespace App\Entity;

use App\Repository\FriendshipsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendshipsRepository::class)]
class Friendships
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $friendshipsId = null;

    #[ORM\Column]
    private ?int $senderId = null;

    #[ORM\Column]
    private ?int $receiverId = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFriendshipsId(): ?int
    {
        return $this->friendshipsId;
    }

    public function setFriendshipsId(int $friendshipsId): static
    {
        $this->friendshipsId = $friendshipsId;

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
}
