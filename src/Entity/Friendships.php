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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "requester_id", referencedColumnName: "id", nullable: false)]
    private ?User $requester;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "addressee_id", referencedColumnName: "id", nullable: false)]
    private ?User $addressee;

    
    #[ORM\Column(type: "string", length: 20)]
    private string $status = 'pending'; // Possible values: 'pending', 'accepted', 'declined'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequester(): User
    {
        return $this->requester;
    }

    public function setRequester(User $requester): self
    {
        $this->requester = $requester;
        return $this;
    }

    public function getAddressee(): User
    {
        return $this->addressee;
    }

    public function setAddressee(User $addressee): self
    {
        $this->addressee = $addressee;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}

