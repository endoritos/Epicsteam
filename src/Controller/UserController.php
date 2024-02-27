<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\GameRepository;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserController extends AbstractController
{
    private $userRepository;
    private $em;

    public function __construct(EntityManagerInterface $em,UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    #[Route('/profile', methods: ['GET'], name: 'user_profile')]
    // #[IsGranted('ROLE_USER')] maby for later use case 
    public function profile(GameRepository $gameRepository, ScoreRepository $scoreRepository): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();
        // If no user is logged in, redirect to the login page
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $games = $gameRepository->findBy(['user' => $user]);

        $hasCreatedGame = !empty($gameRepository->findBy(['user' => $user]));

        $userHasPlayedGame = !empty($scoreRepository->findBy(['user'=> $user]));



        return $this->render('movies/profile.html.twig', [
            'user' => $user,
            'hasCreatedGame' => $hasCreatedGame,
            'userHasPlayedGame'=> $userHasPlayedGame,
            'games' => $games,
        ]);
    }

    
    #[Route('/edit-profile', name: 'app_edit_profile')]
public function editProfile(Request $request, UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em): Response
{

    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Ensure $user is an instance of User which implements PasswordAuthenticatedUserInterface
    if (!$user instanceof \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface) {
        throw new \LogicException('The user entity must implement PasswordAuthenticatedUserInterface.');
    }

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $newPassword = $form->get('password')->getData();
        if ($newPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
        }

        $profilePicture = $form->get('profilePictures')->getData();
        if ($profilePicture) {
            $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$profilePicture->guessExtension();

            try {
                $profilePicture->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
            } catch (FileException $e) {
                return new Response($e->getMessage());
            }
            $user->setProfilePictures('/uploads/'.$newFilename); // Ensure this matches your User entity method
        }

    
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_profile');
    }

    return $this->render('movies/editProfile.html.twig', [
        'form' => $form->createView(),
    ]);
}
}