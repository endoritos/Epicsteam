<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'app_movies')]
    public function index(): Response
    {
        $movies = ["Avengers: endgame", "movies 2","space cats ahmad"];

        return $this->render('movies/index.html.twig', array(
            'movies' => $movies,
        ) 
        );
    }

}
