<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FriendshipsRepository;
use App\Repository\UserRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminDashboardController extends AbstractController
{
    private $em;

    private $gameRepository;

    private $security;

    private $friendshipsRepository;

    public function __construct(EntityManagerInterface $em, GameRepository $movieRepository ,Security $security, FriendshipsRepository $friendshipsRepository)  
    {
        $this->em = $em;
        $this->gameRepository = $movieRepository;
        $this->security = $security;
        $this->friendshipsRepository = $friendshipsRepository;
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(UserRepository $userRepository, GameRepository $gameRepository): Response
    {
        $users = $userRepository->findAll();
        $games = $gameRepository->findAll();

        return $this->render('movies/dashboard.html.twig', [
            'users' => $users,
            'games' => $games,
        ]);
    }
}