<?php

namespace App\Controller;


use App\Entity\Game;
use App\Form\MovieFormType;
use App\Repository\FriendshipsRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

 // findAll() - select all * FORM movies;
        // find() - Select * FORM movies WHERE id= 5
        // findBy() - Select * FORM movies ORDER BY id DESC
        // findOneBy()- Select * FORM movies WHERE id= 7 AND title = 'found love part 2' ORDER BY id DESC
        // count() - SELECT COUNT() FORM movies WHERE id = 7

        // $repository = $this->em->getRepository(Movie::class);

        // $movies = $repository->findOneBy(['id'=>7, 'title'=> 'found love part 2'],['id'=> 'DESC']);
class MoviesController extends AbstractController
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

    #[Route('/games', methods:['GET'], name: 'app_movies')]
    public function index(Request $request, Security $security): Response
    {
        $user = $this->getUser();
        $visibility = $request->query->get('visibility');

        if ($visibility === 'private' && !$security->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('You do not have permission to view private games.');
        }
    
        if ($visibility === 'private') {
            $games = $this->gameRepository->findPrivateGamesForFriends($user);
        }  else {
            
            $games = $this->gameRepository->findBy(['isPublic' => false]);
        }
    
        return $this->render('movies/index.html.twig', [
            'games' => $games
        ]);
    }

    #[Route('/games/create', name: 'create_game')] // Corrected the route name
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $game = new Game();
        $form = $this->createForm(MovieFormType::class, $game); // Use the correct form class
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();
            
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    // Consider logging this error instead of returning a response with the error message
                    return new Response($e->getMessage());
                }

                $game->setImagePath('/uploads/' . $newFileName);
            }

            // Set the current user as the creator of the game
            $user = $this->security->getUser();
            if ($user) {
                $game->setUser($user);
            }

            $em->persist($game);
            $em->flush();

            // Redirect to a route that makes sense after creating a game
            return $this->redirectToRoute('app_movies'); // Ensure this route exists in your routing configuration
        }

        // Adjust the template path to reflect game creation context
        return $this->render('movies/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/games/edit/{id}', name: 'edit_movie')]
    public function edit($id, Request $request): Response 
    {
        // $this->checkLoggedInUser($id);
        $movie = $this->gameRepository->find($id);

        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                if ($movie->getImagePath() !== null) {
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath()
                        )) {
                            $this->GetParameter('kernel.project_dir') . $movie->getImagePath();
                    }
                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                    try {
                        $imagePath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $movie->setImagePath('/uploads/' . $newFileName);
                    $this->em->flush();

                    return $this->redirectToRoute('app_movies');
                }
            } else {
                $movie->setGameName($form->get('gameName')->getData());
                $movie->setLink($form->get('link')->getData());
                $movie->setIsPublic($form->get('isPublic')->getData());

                $this->em->flush();
                return $this->redirectToRoute('app_movies');
            }
        }

        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
    }

    #[Route('/games/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_movie')]
    public function delete($id): Response
    {
        // $this->checkLoggedInUser($id);
        $movie = $this->gameRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('app_movies');
    }


    #[Route('/games/{id}', name: 'game_show')]
    public function show(int $id, GameRepository $gameRepository, Security $security, FriendshipsRepository $friendshipsRepository): Response
    {
        $game = $gameRepository->find($id);
        $user = $security->getUser(); // The currently authenticated user
    
        if (!$game) {
            throw $this->createNotFoundException('The game does not exist.');
        }
    
        $gameCreator = $game->getUser();
        
        // Check for public games or if the user is the game creator themselves
        if ($game->getIsPublic() || $user === $gameCreator) {
            // Allow access to public games and games created by the current user
            return $this->render('movies/show.html.twig', ['game' => $game]);
        }
    
        // At this point, the game is private and not created by the current user
        // Ensure user is logged in
        if (!$user) {
            // Redirect non-authenticated users to the login page
            return $this->redirectToRoute('app_login');
        }
    
        // Perform friendship check for private games
        $friendship = $friendshipsRepository->findAcceptedFriendship($user, $gameCreator);
        
        if (!$friendship) {
            // If there's no accepted friendship, deny access to private game
            throw new AccessDeniedException('You do not have permission to view this private game.');
        }
    
        // The game is private, but the user is a friend of the creator
        return $this->render('movies/show.html.twig', ['game' => $game]);
    }
    
    
}