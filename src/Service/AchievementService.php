<?php

namespace App\Service;

use App\Entity\Score;
use App\Entity\UserAchievements;
use App\Repository\AchievementRepository;
use Doctrine\ORM\EntityManagerInterface;


class AchievementService
{
    private EntityManagerInterface $entityManager;
    private AchievementRepository $achievementRepository;

    public function __construct(EntityManagerInterface $entityManager, AchievementRepository $achievementRepository)
    {
        $this->entityManager = $entityManager;
        $this->achievementRepository = $achievementRepository;
    }

    public function checkAndAwardScoreAchievements(Score $score,AchievementRepository $achievementRepository): void
    {
        $user = $score->getUser();

        $achievementName = "First Win";
        $achievement = $achievementRepository->findOneByAchievementName($achievementName);

        // Example condition: Award achievement for a high score
        if ($score->getScore() > 100) {
            $achievement = $this->achievementRepository->findOneByTitle('topScore');
            if ($achievement && !$this->hasUserAchieved($user, $achievement)) {
                $userAchievement = new UserAchievements();
                $userAchievement->setUser($user)
                                ->setAchievement($achievement)
                                ->setEarnedAt(new \DateTime());

                $this->entityManager->persist($userAchievement);
                $this->entityManager->flush();
            }
        }
    }

    private function hasUserAchieved($user, $achievement): bool
    {
        // Implement logic to check if the user already has this achievement
        return 1;
    }
}
