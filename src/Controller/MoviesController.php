<?php

namespace App\Controller;


use App\Entity\Game;
use App\Form\MovieFormType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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



    public function __construct(EntityManagerInterface $em, GameRepository $movieRepository ,Security $security, )  
    {
        $this->em = $em;
        $this->gameRepository = $movieRepository;
        $this->security = $security;
    }

    #[Route('/games', methods:['GET'] , name: 'app_movies')]
    public function index(): Response
    {
        $games = $this->gameRepository->findAll();

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


    #[Route('/games/{id}', methods:['GET'], name: 'movies_show')]
    public function show($id): Response
    {
        $game = $this->gameRepository->find($id);
        
        return $this->render('movies/show.html.twig', [
            'game' => $game
        ]);
    }
}