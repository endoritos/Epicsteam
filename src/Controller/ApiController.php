<?php 
namespace App\Controller;

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
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiController extends AbstractController
{

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
    public function saveScore(Request $request, EntityManagerInterface $em): Response
    {
        // Verwerk de request data en sla de score op
        return $this->json(['message' => 'Score opgeslagen']);
    }

    #[Route('/endy/achievement', name: 'create_achievement', methods: ['POST'])]
    public function createAchievement(Request $request, EntityManagerInterface $em): Response
    {
        // Verwerk de request data en maak een achievement aan
        return $this->json(['message' => 'Achievement aangemaakt']);
    }

    #[Route('/api/users/{id}/achievements', methods: ['GET'])]
public function getUserAchievements(int $id, UserRepository $userRepository): JsonResponse {
    $user = $userRepository->find($id);
    $achievements = []; // Fetch achievements for $user

    // Transform $achievements to array or DTOs

    return new JsonResponse($achievements);
}
} 