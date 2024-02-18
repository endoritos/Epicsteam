<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Friendships;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FriendshipService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function sendFriendRequest(User $requester, User $addressee): void
    {
            // Check for an existing request between these users
        $existingRequest = $this->entityManager->getRepository(Friendships::class)
        ->findOneBy([
            'requester' => $requester,
            'addressee' => $addressee,
            // Optionally check 'status' => 'pending'
        ]);

            if (!$existingRequest) {
            $friendship = new Friendships();
            $friendship->setRequester($requester);
            $friendship->setAddressee($addressee);
            $friendship->setStatus('pending'); // Explicitly set status, though default is 'pending'


            $this->entityManager->persist($friendship);
            $this->entityManager->flush();
        }
    }

    public function acceptFriendRequest(Friendships $friendship): void
    {
        $friendship->setStatus('accepted');
        $this->entityManager->flush();
    }

    public function declineFriendRequest(Friendships $friendship): void
    {
        $friendship->setStatus('declined');
        $this->entityManager->flush();
    }

    public function checkFriendshipStatus(User $userOne, User $userTwo): ?string
    {
        $friendship = $this->entityManager->getRepository(Friendships::class)->findOneBy([
            'requester' => $userOne,
            'addressee' => $userTwo,
        ]);

        if (!$friendship) {
            $friendship = $this->entityManager->getRepository(Friendships::class)->findOneBy([
                'requester' => $userTwo,
                'addressee' => $userOne,
            ]);
        }

        return $friendship ? $friendship->getStatus() : null;
    }

}
