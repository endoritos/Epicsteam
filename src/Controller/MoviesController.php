<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    private $movieRepository;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository) 
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
    }

    #[Route('/movies', methods:['GET'] , name: 'app_movies')]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
        

        return $this->render('movies/index.html.twig', [
            'movies' => $movies
        ]);
    }


    #[Route('/movies/{id}', methods:['GET'], name: 'app_movies')]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);
        
        return $this->render('movies/show.html.twig', [
            'movie' => $movie
        ]);
    }
}