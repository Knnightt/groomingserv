<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Check if user is already logged in using AbstractController helper
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }
        
        // Only show home page to non-logged in users
        return $this->render('home/index.html.twig');
    }
}