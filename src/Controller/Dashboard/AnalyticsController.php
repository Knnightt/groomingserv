<?php

namespace App\Controller\Dashboard;

use App\Repository\AppointmentRepository;
use App\Repository\PetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/analytics')]
class AnalyticsController extends AbstractController
{
    #[Route('/', name: 'app_analytics_index', methods: ['GET'])]
    public function index(
        AppointmentRepository $appointmentRepository,
        UserRepository $userRepository,
        PetRepository $petRepository
    ): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        // Calculate total revenue from ALL COMPLETED appointments
        $completedAppointments = $appointmentRepository->findBy(['status' => 'Completed']);
        $totalRevenue = 0;
        foreach ($completedAppointments as $appointment) {
            $totalRevenue += $appointment->getTotalAmount() ?? 0;
        }
        
        // Calculate today's revenue
        $todayRevenue = $appointmentRepository->getTodayRevenue();
        
        // Calculate revenue change based on this month vs last month
        $currentMonth = new \DateTimeImmutable('first day of this month');
        $lastMonth = new \DateTimeImmutable('first day of last month');
        
        $currentMonthRevenue = $this->calculateMonthlyRevenue($appointmentRepository, $currentMonth);
        $lastMonthRevenue = $this->calculateMonthlyRevenue($appointmentRepository, $lastMonth);
        
        if ($lastMonthRevenue > 0) {
            $revenueChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } else {
            $revenueChange = $currentMonthRevenue > 0 ? 100 : 0;
        }
        
        // Get other stats
        $totalUsers = $userRepository->count([]);
        $totalPets = $petRepository->count([]);
        $totalAppointments = $appointmentRepository->count([]);
        $pendingAppointments = $appointmentRepository->count(['status' => 'Pending']);
        
        // Get today's appointments
        $todaysAppointments = $appointmentRepository->findTodayAppointments();
        
        // Get today's completed appointments
        $todaysCompletedCount = $appointmentRepository->countCompletedToday();

        // Get recent appointments for activities
        $recentAppointments = $appointmentRepository->findRecentAppointments(10);
        $recentActivities = [];
        
        foreach ($recentAppointments as $appointment) {
            // FIXED: Changed getFullName() to getDisplayName()
            $customerName = $appointment->getCustomer() ? 
                $appointment->getCustomer()->getDisplayName() : 
                'Unknown Customer';
                
            $recentActivities[] = [
                'user' => $customerName,
                'action' => ucfirst($appointment->getStatus()) . ' appointment',
                'pet' => $appointment->getPet() ? $appointment->getPet()->getName() : null,
                'service' => $appointment->getService() ? $appointment->getService()->getName() : null,
                'time' => $this->formatTimeAgo($appointment->getCreatedAt()),
                'amount' => $appointment->getTotalAmount(),
            ];
        }

        // Get appointment status distribution for chart
        $appointmentStatus = [
            'Completed' => $appointmentRepository->count(['status' => 'Completed']),
            'Confirmed' => $appointmentRepository->count(['status' => 'Confirmed']),
            'Pending' => $pendingAppointments,
            'Cancelled' => $appointmentRepository->count(['status' => 'Cancelled']),
        ];

        // Get pet species stats
        $pets = $petRepository->findAll();
        $petSpeciesStats = [];
        foreach ($pets as $pet) {
            $species = $pet->getSpecies() ?? 'Other';
            $petSpeciesStats[$species] = ($petSpeciesStats[$species] ?? 0) + 1;
        }

        // Sort by count
        arsort($petSpeciesStats);

        return $this->render('dashboard/analytics/index.html.twig', [
            // Stats
            'total_revenue' => $totalRevenue,
            'revenue_change' => $revenueChange,
            'today_revenue' => $todayRevenue,
            'total_users' => $totalUsers,
            'total_pets' => $totalPets,
            'total_appointments' => $totalAppointments,
            'pending_appointments' => $pendingAppointments,
            'todays_appointments' => $todaysAppointments,
            'todays_appointments_count' => count($todaysAppointments),
            'todays_completed_count' => $todaysCompletedCount,
            
            // Chart data
            'appointment_status' => json_encode(array_values($appointmentStatus)),
            'pet_species_stats' => json_encode(array_values($petSpeciesStats)),
            'pet_species_labels' => json_encode(array_keys($petSpeciesStats)),
            
            // Recent data
            'recent_activities' => $recentActivities,
            'recent_appointments' => array_slice($recentAppointments, 0, 5),
            
            // For appointment change calculation
            'current_month_revenue' => $currentMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,
        ]);
    }

    private function calculateMonthlyRevenue(AppointmentRepository $repository, \DateTimeInterface $month): float
    {
        $start = new \DateTimeImmutable($month->format('Y-m-01 00:00:00'));
        $end = $start->modify('last day of this month 23:59:59');
        
        $appointments = $repository->findBy([
            'status' => 'Completed',
        ]);
        
        $revenue = 0;
        foreach ($appointments as $appointment) {
            $appointmentDate = $appointment->getStartAt();
            if ($appointmentDate >= $start && $appointmentDate <= $end) {
                $revenue += $appointment->getTotalAmount() ?? 0;
            }
        }
        
        return $revenue;
    }

    private function formatTimeAgo(\DateTimeInterface $dateTime): string
    {
        $now = new \DateTimeImmutable();
        $diff = $now->diff($dateTime);
        
        if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        
        return 'Just now';
    }

    #[Route('/revenue-data', name: 'app_analytics_revenue_data', methods: ['GET'])]
    public function revenueData(AppointmentRepository $appointmentRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        // Generate last 6 months revenue data
        $revenueData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = new \DateTimeImmutable("first day of -{$i} months");
            $revenue = $this->calculateMonthlyRevenue($appointmentRepository, $month);
            $revenueData[$month->format('M Y')] = $revenue;
            $labels[] = $month->format('M Y');
        }
        
        return $this->json([
            'labels' => $labels,
            'data' => array_values($revenueData),
        ]);
    }

    #[Route('/appointment-stats', name: 'app_analytics_appointment_stats', methods: ['GET'])]
    public function appointmentStats(AppointmentRepository $appointmentRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $stats = [
            'Pending' => $appointmentRepository->count(['status' => 'Pending']),
            'Confirmed' => $appointmentRepository->count(['status' => 'Confirmed']),
            'Completed' => $appointmentRepository->count(['status' => 'Completed']),
            'Cancelled' => $appointmentRepository->count(['status' => 'Cancelled']),
        ];
        
        return $this->json([
            'labels' => array_keys($stats),
            'data' => array_values($stats),
        ]);
    }

    #[Route('/user-growth', name: 'app_analytics_user_growth', methods: ['GET'])]
    public function userGrowth(UserRepository $userRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        // Generate last 6 months user growth data
        $growthData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = new \DateTimeImmutable("first day of -{$i} months");
            $end = $month->modify('last day of this month 23:59:59');
            
            $count = $userRepository->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.createdAt <= :end')
                ->setParameter('end', $end)
                ->getQuery()
                ->getSingleScalarResult();
            
            $growthData[$month->format('M Y')] = (int) $count;
            $labels[] = $month->format('M');
        }
        
        return $this->json([
            'labels' => $labels,
            'data' => array_values($growthData),
        ]);
    }

    #[Route('/pet-stats', name: 'app_analytics_pet_stats', methods: ['GET'])]
    public function petStats(PetRepository $petRepository): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $pets = $petRepository->findAll();
        $stats = [];
        
        foreach ($pets as $pet) {
            $species = $pet->getSpecies() ?? 'Other';
            $stats[$species] = ($stats[$species] ?? 0) + 1;
        }
        
        arsort($stats);
        
        // Limit to top 5 species
        $stats = array_slice($stats, 0, 5, true);
        
        return $this->json([
            'labels' => array_keys($stats),
            'data' => array_values($stats),
        ]);
    }

    #[Route('/recent-activity', name: 'app_analytics_recent_activity', methods: ['GET'])]
    public function recentActivity(AppointmentRepository $appointmentRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $recentAppointments = $appointmentRepository->findRecentAppointments(20);
        $activities = [];
        
        foreach ($recentAppointments as $appointment) {
            // FIXED: Changed getFullName() to getDisplayName()
            $customerName = $appointment->getCustomer() ? 
                $appointment->getCustomer()->getDisplayName() : 
                'Unknown Customer';
                
            $activities[] = [
                'user' => $customerName,
                'action' => ucfirst($appointment->getStatus()) . ' appointment',
                'pet' => $appointment->getPet() ? $appointment->getPet()->getName() : null,
                'service' => $appointment->getService() ? $appointment->getService()->getName() : null,
                'time' => $this->formatTimeAgo($appointment->getCreatedAt()),
                'amount' => $appointment->getTotalAmount(),
            ];
        }

        return $this->render('dashboard/analytics/_recent_activity.html.twig', [
            'activities' => $activities,
        ]);
    }
}