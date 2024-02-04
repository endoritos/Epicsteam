<?php

namespace App\Entity;

use App\Repository\LeaderBoardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaderBoardRepository::class)]
class LeaderBoard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $leaderBoardId = null;

    #[ORM\Column]
    private ?int $gameId = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $Playedtime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeaderBoardId(): ?int
    {
        return $this->leaderBoardId;
    }

    public function setLeaderBoardId(int $leaderBoardId): static
    {
        $this->leaderBoardId = $leaderBoardId;

        return $this;
    }

    public function getGameId(): ?int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): static
    {
        $this->gameId = $gameId;

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

    public function getPlayedtime(): ?\DateTimeInterface
    {
        return $this->Playedtime;
    }

    public function setPlayedtime(\DateTimeInterface $Playedtime): static
    {
        $this->Playedtime = $Playedtime;

        return $this;
    }
}
