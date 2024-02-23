<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\JoinColumn(nullable: true)]
    private Game $game;

    #[ORM\Column(type: 'string', length: 255)]
    private string $achievementName;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $achievementDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    public function getAchievementName(): string
    {
        return $this->achievementName;
    }

    public function setAchievementName(string $achievementName): self
    {
        $this->achievementName = $achievementName;
        return $this;
    }

    public function getAchievementDate(): \DateTimeInterface
    {
        return $this->achievementDate;
    }

    public function setAchievementDate(\DateTimeInterface $achievementDate): self
    {
        $this->achievementDate = $achievementDate;
        return $this;
    }
}

