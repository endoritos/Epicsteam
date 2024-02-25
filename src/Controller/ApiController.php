<?php 
namespace App\Controller;

use App\Entity\Score;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\GameRepository;
use App\Repository\ScoreRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiController extends AbstractController
{


    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private GameRepository $gameRepository;
    private ScoreRepository $scoreRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, GameRepository $gameRepository, ScoreRepository $scoreRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
        $this->scoreRepository = $scoreRepository;
    }

    private function generateHashForUserAndGame($user, $game): string
    {
        //  gameId and gameApi for hash generation
        return sha1($user->getId() . $game->getGameApi() . 'STAYWOKE');
    }
            // endy u have to decod is code 

    #[Route('/endy/user/info', name: 'get_user_info', methods: ['GET'])]
    public function getUserInfo(Request $request, UserRepository $userRepository, GameRepository $gameRepository): Response
    {
        $userId = $request->query->get('userId');
        $gameId = $request->query->get('gameId'); 
        $receivedHash = $request->query->get('hash');

        
        $game = $gameRepository->find($gameId);
        if (!$game) {
            return $this->json(['error' => 'Game not found'], Response::HTTP_NOT_FOUND);
        }

        
        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // endy Validete the hash using the gameApi key from the Game entity
        $expectedHash = $this->generateHashForUserAndGame($user, $game); 
        if ($receivedHash !== $expectedHash) {
            throw new UnauthorizedHttpException('Invalid hash');
        }

        return $this->json([
            'name' => $user->getUsername(),
            'photo' => $user->getProfilePictures(),
        ]);
    }


    #[Route('/endy/score', name: 'save_score', methods: ['POST'])]
    public function saveScore(Request $request, EntityManagerInterface $em, UserRepository $userRepository, GameRepository $gameRepository): Response
    {
        $userId = $request->request->get('userId');
        $gameId = $request->request->get('gameId');
        $receivedHash = $request->request->get('hash');
        $scoreValue = (int) $request->request->get('score');
        $bestTime = (int) $request->request->get('bestTime');

        $user = $userRepository->find($userId);
        $game = $gameRepository->find($gameId);

        if (!$user || !$game) {
            return $this->json(['error' => 'User or Game not found'], Response::HTTP_NOT_FOUND);
        }

        $expectedHash = $this->generateHashForUserAndGame($user, $game);
        if ($receivedHash !== $expectedHash) {
            throw new UnauthorizedHttpException('', 'Invalid hash');
        }

        $score = new Score();
        $score->setUser($user);
        $score->setGame($game);
        $score->setScore($scoreValue);
        $score->setTopScore($bestTime);

        $em->persist($score);
        $em->flush();

        return $this->json(['message' => 'New score saved successfully']);
    }


    #[Route('/api/users/{id}/achievements', methods: ['GET'])]
public function getUserAchievements(int $id, UserRepository $userRepository): JsonResponse {
    $user = $userRepository->find($id);
    $achievements = []; // Fetch achievements for $user

    // Transform $achievements to array or DTOs

    return new JsonResponse($achievements);
}
} 