<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Friendships;
use App\Repository\FriendshipsRepository;
use App\Repository\MessageRepository;
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
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Message;




class FriendshipController extends AbstractController
{
    private $friendshipService;
    private $entityManager;
    private $csrfTokenManager;
    private $requestStack;
    private $friendshipsRepository;

    private $messageRepository;

    public function __construct(FriendshipService $friendshipService, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager, RequestStack $requestStack, FriendshipsRepository $friendshipsRepository, MessageRepository $messageRepository) {
        $this->friendshipService = $friendshipService;
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestStack = $requestStack;
        $this->friendshipsRepository = $friendshipsRepository;
        $this->messageRepository = $messageRepository;
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

        // user status 
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
    public function viewRequests(EntityManagerInterface $entityManager): Response {
        $user = $this->getUser();
    
        
        $unreadMessages = $this->messageRepository->findBy(['receiver' => $user, 'isRead' => false]);
        foreach ($unreadMessages as $message) {
            $message->setIsRead(true);
            $entityManager->persist($message);
        }
        $entityManager->flush();
    

        $receivedRequests = $this->friendshipsRepository->getReceivedFriendRequests($user);
        $csrfTokensAccept = [];
        $csrfTokensDecline = [];
        foreach ($receivedRequests as $request) {
            $csrfTokensAccept[$request->getId()] = $this->csrfTokenManager->getToken('accept' . $request->getId())->getValue();
            $csrfTokensDecline[$request->getId()] = $this->csrfTokenManager->getToken('decline' . $request->getId())->getValue();
        }
    
        $acceptedFriendships = $this->friendshipsRepository->findAcceptedFriendships($user);
        $friends = [];
        foreach ($acceptedFriendships as $friendship) {
            $friend = $user === $friendship->getRequester() ? $friendship->getAddressee() : $friendship->getRequester();
            $friends[] = [
                'id' => $friend->getId(),
                'username' => $friend->getUsername(),
            ];
        }
    
        return $this->render('movies/messages.html.twig', [
            'receivedRequests' => $receivedRequests,
            'csrfTokensAccept' => $csrfTokensAccept,
            'csrfTokensDecline' => $csrfTokensDecline,
            'friends' => $friends, // Pass the list of friends to the template
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

    #[Route('/load-chat-messages/{friendId}', name: 'load_chat_messages', methods: ['GET'])]
    public function loadChatMessages(int $friendId, MessageRepository $messageRepository): JsonResponse
    {
        $user = $this->getUser();
        $messages = $messageRepository->findMessagesBetweenUsers($user->getId(), $friendId);

        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'from' => $message->getSender()->getUsername(),
                'message' => $message->getMessage(),
                // Add more fields as needed
            ];
        }

        return $this->json($formattedMessages);
    }

    #[Route('/send-message', name: 'send_message', methods: ['POST'])]
public function sendMessage(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $user = $this->getUser(); 
    $receiverId = $request->request->get('receiverId');
    $messageText = $request->request->get('message');

    if (!$user || !$receiverId || !$messageText) {
        return $this->json(['status' => 'error', 'message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
    }

    $receiver = $entityManager->getRepository(User::class)->find($receiverId);
    if (!$receiver) {
        return $this->json(['status' => 'error', 'message' => 'Receiver not found'], Response::HTTP_NOT_FOUND);
    }

    $message = new Message();
    $message->setSender($user);
    $message->setReceiver($receiver);
    $message->setMessage($messageText);
    $message->setCreatedAt(new \DateTimeImmutable());
    $message->setIsRead(false);

    $entityManager->persist($message);
    $entityManager->flush();

    return $this->json(['status' => 'success', 'message' => 'Message sent successfully']);
}

#[Route('/admin/user/{userId}/{action}', name: 'admin_block_user', methods: ['POST'])]
public function blockUnblockUser(int $userId, string $action, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response {
    $csrfToken = $request->request->get('_csrf_token');

    if ($action === 'block' && !$this->isCsrfTokenValid('block-user', $csrfToken)) {
        throw new AccessDeniedException('Invalid CSRF token for blocking user.');
    }

    if ($action === 'unblock' && !$this->isCsrfTokenValid('unblock-user', $csrfToken)) {
        throw new AccessDeniedException('Invalid CSRF token for unblocking user.');
    }

    $user = $userRepository->find($userId);
    if (!$user) {
        throw $this->createNotFoundException('User not found.');
    }

    if ($action === 'block') {
        $user->setIsBlocked(true);
    } elseif ($action === 'unblock') {
        $user->setIsBlocked(false);
    } else {
        throw new \InvalidArgumentException('Invalid action.');
    }

    $entityManager->flush();

    $this->addFlash('success', $action === 'block' ? 'User blocked successfully!' : 'User unblocked successfully!');
    return $this->redirectToRoute('app_friends');
}


#[Route('/admin/user/{userId}/toggle-message', name: 'admin_toggle_message_sending', methods: ['POST'])]
public function toggleMessageSending(int $userId, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response {
    $csrfToken = new CsrfToken('toggle-message-' . $userId, $request->request->get('_csrf_token'));

    if (!$csrfTokenManager->isTokenValid($csrfToken)) {
        throw new AccessDeniedException('Invalid CSRF token.');
    }

    $user = $userRepository->find($userId);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

    $action = $request->request->get('action');

    // Now, check the action value to decide what to do
    if ($action === 'block-messages') {
        $user->setCanSendMessages(false);
    } elseif ($action === 'allow-messages') {
        $user->setCanSendMessages(true);
    } else {
        throw new \InvalidArgumentException('Invalid action.');
    }

    $entityManager->flush();

    $this->addFlash('success', $user->getCanSendMessages() ? 'User is now allowed to send messages.' : 'User is now blocked from sending messages.');
    return $this->redirectToRoute('app_friends');
}

// Assuming you might also want a similar function for friend requests
#[Route('/admin/user/{userId}/toggle-friend-request', name: 'admin_toggle_friend_request', methods: ['POST'])]
    public function toggleFriendRequestSending(int $userId, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response {
        $csrfToken = new CsrfToken('toggle-friend-request-' . $userId, $request->request->get('_csrf_token'));

        if (!$csrfTokenManager->isTokenValid($csrfToken)) {
            throw new AccessDeniedException('Invalid CSRF token.');
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $action = $request->request->get('action');
        if ($action === 'block-friend-request') {
            $user->setCanSendFriendRequests(true);
        } elseif ($action === 'allow-friend-requests') {
            $user->setCanSendFriendRequests(false);
        } else {
            throw new \InvalidArgumentException('Invalid action');
        }
        

        // Toggle the ability to send friend requests
        $user->setCanSendFriendRequests(!$user->getCanSendFriendRequests());
        $entityManager->flush();

        $this->addFlash('success', $user->getCanSendFriendRequests() ? 'User is now allowed to send friend requests.' : 'User is now blocked from sending friend requests.');
        return $this->redirectToRoute('app_friends');
    }
}