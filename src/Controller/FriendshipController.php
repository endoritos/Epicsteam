<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\FriendshipService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Repository\UserRepository;


class FriendshipController extends AbstractController
{
    private $friendshipService;
    private $entityManager;


    public function __construct(FriendshipService $friendshipService, EntityManagerInterface $entityManager)
    {
        $this->friendshipService = $friendshipService;
        $this->entityManager = $entityManager;
    }

    #[Route('friends', name: 'app_friends')]
    public function listFriends(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllUsernamesAndPictures();

        return $this->render('movies/requests.html.twig', [
            'users' => $users,
        ]);
    }

//     #[Route('/friend/request/{addresseeId}', name: 'friend_request', methods: ['POST'])]
//     public function sendRequest(int $addresseeId): Response
//     {
//         $requester = $this->getUser();
//         $addressee = $this->entityManager->getRepository(User::class)->find($addresseeId);

//         if (!$addressee) {
//             throw $this->createNotFoundException('User not found.');
//         }

//         if ($requester->getId() === $addressee->getId()) {
//             throw new \Exception("You cannot send a friend request to yourself.");
//         }

//         $this->friendshipService->sendFriendRequest($requester, $addressee);

//         // Redirect to a page (e.g., list of users or friend requests) or return a JSON response
//         return $this->redirectToRoute('some_route_after_sending_request');
//     }

//     #[Route('/friend/requests', name: 'view_friend_requests', methods: ['GET'])]
//     public function viewRequests(): Response
//     {
//         $user = $this->getUser();
//         $receivedRequests = $this->friendshipService->getReceivedFriendRequests($user);
//         $sentRequests = $this->friendshipService->getSentFriendRequests($user);

//         // Render a template to show the friend requests
//         return $this->render('movies/requests.html.twig', [
//             'receivedRequests' => $receivedRequests,
//             'sentRequests' => $sentRequests,
//         ]);
//     }

//     #[Route('/friend/respond/{requestId}/{action}', name: 'respond_to_request', methods: ['POST'])]
//     public function respondToRequest(int $requestId, string $action): Response
//     {
//         $friendship = $this->entityManager->getRepository(Friendships::class)->find($requestId);

//         if (!$friendship) {
//             throw $this->createNotFoundException('Friend request not found.');
//         }

//         if ($friendship->getAddressee() !== $this->getUser()) {
//             throw new AccessDeniedException('You are not authorized to respond to this friend request.');
//         }

//         if ($action === 'accept') {
//             $this->friendshipService->acceptFriendRequest($friendship);
//         } elseif ($action === 'decline') {
//             $this->friendshipService->declineFriendRequest($friendship);
//         } else {
//             throw new \InvalidArgumentException('Invalid action.');
//         }

//         // Redirect after responding
//         return $this->redirectToRoute('view_friend_requests');
//     }

//     // Add more methods as needed for your application's functionality
 }
