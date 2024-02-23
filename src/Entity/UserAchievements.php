<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserAchievements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Achievement::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Achievement $achievement = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $earnedAt = null;

    // Constructor
    public function __construct()
    {
        $this->earnedAt = new \DateTime(); // Default to current time
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getAchievement(): ?Achievement
    {
        return $this->achievement;
    }

    public function setAchievement(?Achievement $achievement): self
    {
        $this->achievement = $achievement;
        return $this;
    }

    public function getEarnedAt(): ?\DateTimeInterface
    {
        return $this->earnedAt;
    }

    public function setEarnedAt(\DateTimeInterface $earnedAt): self
    {
        $this->earnedAt = $earnedAt;
        return $this;
    }
}