<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentRedirectController extends AbstractController
{
    #[Route('/appointment/', name: 'app_appointment_redirect', methods: ['GET'])]
    public function redirectToScheduling(): RedirectResponse
    {
        return $this->redirectToRoute('app_scheduling_index');
    }

    #[Route('/appointment', methods: ['GET'])]
    public function redirectToSchedulingNoSlash(): RedirectResponse
    {
        return $this->redirectToRoute('app_scheduling_index');
    }
}
