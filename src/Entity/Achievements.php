<?php

namespace App\Entity;

use App\Repository\AchievementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchievementsRepository::class)]
class Achievements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $achievementId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $achivedDate = null;

    #[ORM\Column]
    private ?int $gameID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAchievementId(): ?int
    {
        return $this->achievementId;
    }

    public function setAchievementId(int $achievementId): static
    {
        $this->achievementId = $achievementId;

        return $this;
    }

    public function getAchivedDate(): ?\DateTimeInterface
    {
        return $this->achivedDate;
    }

    public function setAchivedDate(\DateTimeInterface $achivedDate): static
    {
        $this->achivedDate = $achivedDate;

        return $this;
    }

    public function getGameID(): ?int
    {
        return $this->gameID;
    }

    public function setGameID(int $gameID): static
    {
        $this->gameID = $gameID;

        return $this;
    }
}
