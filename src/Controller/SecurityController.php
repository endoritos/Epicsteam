<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // If user is already logged in, redirect them to the homepage or any other desired route
        if ($this->getUser()) {
            return $this->redirectToRoute('app_movies');
        }

        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Get the last email entered by the user
        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_email' => $lastEmail, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // This method can be blank because it will be intercepted by the logout key on your firewall
        // The actual logout process will be handled by Symfony's security system
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
