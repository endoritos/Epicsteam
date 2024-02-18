<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Friendships;
use App\Repository\FriendshipsRepository;
use App\Service\FriendshipService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RequestStack;



class FriendshipController extends AbstractController
{
    private $friendshipService;
    private $entityManager;
    private $csrfTokenManager;
    private $requestStack;
    private $friendshipsRepository;

    public function __construct(FriendshipService $friendshipService, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager, RequestStack $requestStack, FriendshipsRepository $friendshipsRepository) {
        $this->friendshipService = $friendshipService;
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestStack = $requestStack;
        $this->friendshipsRepository = $friendshipsRepository;
    }

    #[Route('/friends', name: 'app_friends')]
    public function listFriends(Request $request, UserRepository $userRepository, FriendshipsRepository $friendshipRepository): Response {
        $usernameFilter = $request->query->get('username');
        $csrfToken = $this->csrfTokenManager->getToken('friend-request')->getValue();
        $currentUser = $this->getUser();

        if ($usernameFilter) {
            $users = $userRepository->findByUsernameLike($usernameFilter);
        } else {
            $users = $userRepository->findAllUsernamesAndPictures();
        }

        // New code to fetch friendship statuses
        $friendshipStatuses = [];
        foreach ($users as $user) {
            if ($currentUser && $user['id'] !== $currentUser->getId()) {
                $status = $friendshipRepository->findFriendshipStatus($currentUser->getId(), $user['id']);
                $friendshipStatuses[$user['id']] = $status;
            }
        }
        $acceptedFriendships = $friendshipRepository->findAcceptedFriendships($currentUser);

        return $this->render('movies/requests.html.twig', [
            'users' => $users,
            'csrf_token' => $csrfToken,
            'friendshipStatuses' => $friendshipStatuses,
            'acceptedFriendships' => $acceptedFriendships, // Pass statuses to the template
        ]);
    }

    #[Route('/friend/request/{addresseeId}', name: 'friend_request', methods: ['POST'])]
    public function sendRequest(int $addresseeId): Response {
        $request = $this->requestStack->getCurrentRequest();
        $requester = $this->getUser();
        $addressee = $this->entityManager->getRepository(User::class)->find($addresseeId);
    
          // Retrieve and validate the CSRF token
    $token = $request->request->get('_csrf_token');
    if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('friend-request', $token))) {
        throw new AccessDeniedException('Invalid CSRF token.');
    }

        if (!$addressee) {
            throw $this->createNotFoundException('User not found.');
        }

        if ($requester->getId() === $addressee->getId()) {
            throw new \Exception("You cannot send a friend request to yourself.");
        }

        $this->friendshipService->sendFriendRequest($requester, $addressee);

        $this->addFlash('success', 'Friend request sent successfully!');
        return $this->redirectToRoute('app_friends');
    }

    #[Route('/messages', name: 'app_messages', methods: ['GET'])]
    public function viewRequests(): Response {
        $user = $this->getUser();
        $receivedRequests = $this->friendshipsRepository->getReceivedFriendRequests($user);
    
        $csrfTokensAccept = [];
        $csrfTokensDecline = [];
        foreach ($receivedRequests as $request) {
            $csrfTokensAccept[$request->getId()] = $this->csrfTokenManager->getToken('accept' . $request->getId())->getValue();
            $csrfTokensDecline[$request->getId()] = $this->csrfTokenManager->getToken('decline' . $request->getId())->getValue();
        }
    
        return $this->render('movies/messages.html.twig', [
            'receivedRequests' => $receivedRequests,
            'csrfTokensAccept' => $csrfTokensAccept,
            'csrfTokensDecline' => $csrfTokensDecline,
        ]);
    }
    


    #[Route('/friend/respond/{requestId}/{action}', name: 'respond_to_request', methods: ['POST'])]
    public function respondToRequest(Request $request, int $requestId, string $action): Response {
        // Validate CSRF token
        $csrfToken = $request->request->get('_csrf_token');
        $tokenValid = $this->csrfTokenManager->isTokenValid(new CsrfToken($action . $requestId, $csrfToken));
    
        if (!$tokenValid) {
            throw new AccessDeniedException('Invalid CSRF token.');
        }
        $friendship = $this->entityManager->getRepository(Friendships::class)->find($requestId);

        if (!$friendship) {
            throw $this->createNotFoundException('Friend request not found.');
        }

        if ($friendship->getAddressee() !== $this->getUser()) {
            throw new AccessDeniedException('You are not authorized to respond to this friend request.');
        }

        if ($action === 'accept') {
            $this->friendshipService->acceptFriendRequest($friendship);
        } elseif ($action === 'decline') {
            $this->friendshipService->declineFriendRequest($friendship);
        } else {
            throw new \InvalidArgumentException('Invalid action.');
        }

        // Redirect after responding
        return $this->redirectToRoute('app_messages');
    }

    // Add more methods as needed for your application's functionality
}
