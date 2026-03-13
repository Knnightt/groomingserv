<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use App\Repository\PetRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        AppointmentRepository $appointmentRepository,
        PetRepository $petRepository,
        ServiceRepository $serviceRepository,
        UserRepository $userRepository
    ): Response {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Common data for all roles
        $data = [
            'user' => $user,
            'recent_appointments' => [],
            'stats' => []
        ];

        // Role-specific data
        if ($this->isGranted('ROLE_ADMIN')) {
            // Admin dashboard data
            $data['stats'] = [
                'total_users' => $userRepository->count([]),
                'total_appointments' => $appointmentRepository->count([]),
                'total_pets' => $petRepository->count([]),
                'total_services' => $serviceRepository->count([]),
                'revenue_today' => $appointmentRepository->getTodayRevenue(),
                'pending_appointments' => $appointmentRepository->count(['status' => 'Pending']),
            ];
            $data['recent_appointments'] = $appointmentRepository->findRecentAppointments(10);
            
        } elseif ($this->isGranted('ROLE_MANAGER')) {
            // Manager dashboard data
            $data['stats'] = [
                'today_appointments' => $appointmentRepository->countTodayAppointments(),
                'completed_today' => $appointmentRepository->countCompletedToday(),
                'pending_appointments' => $appointmentRepository->count(['status' => 'Pending']),
                'revenue_today' => $appointmentRepository->getTodayRevenue(),
            ];
            $data['recent_appointments'] = $appointmentRepository->findTodayAppointments();
            
        } elseif ($this->isGranted('ROLE_STAFF')) {
            // Staff dashboard data
            $staff = $user->getStaff();
            $data['stats'] = [
                'my_appointments_today' => $appointmentRepository->countStaffAppointmentsToday($staff),
                'completed_today' => $appointmentRepository->countStaffCompletedToday($staff),
                'pending_today' => $appointmentRepository->countStaffPendingToday($staff),
            ];
            $data['recent_appointments'] = $appointmentRepository->findStaffAppointmentsToday($staff);
            
        } else {
            // Regular user dashboard data
            $data['stats'] = [
                'my_pets' => $petRepository->count(['owner' => $user]),
                'upcoming_appointments' => $appointmentRepository->countUpcomingUserAppointments($user),
                'total_appointments' => $appointmentRepository->count(['customer' => $user]),
                'completed_appointments' => $appointmentRepository->count(['customer' => $user, 'status' => 'Completed']),
            ];
            $data['recent_appointments'] = $appointmentRepository->findUserRecentAppointments($user, 5);
            $data['my_pets'] = $petRepository->findBy(['owner' => $user], ['name' => 'ASC']);
        }

        return $this->render('dashboard/index.html.twig', $data);
    }
}