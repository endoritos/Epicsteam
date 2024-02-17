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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="requester_id", referencedColumnName="id", nullable=false)
     */
    private $requester;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="addressee_id", referencedColumnName="id", nullable=false)
     */
    private $addressee;

    /**
     * @ORM\Column(type="string", length=20)
     * Possible values: 'pending', 'accepted', 'declined'
     */
    private string $status = 'pending';

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

