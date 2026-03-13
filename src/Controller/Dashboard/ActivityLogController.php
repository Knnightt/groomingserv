<?php

namespace App\Controller\Dashboard;

use App\Repository\UserRepository;
use App\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/activity-logs')]
class ActivityLogController extends AbstractController
{
    #[Route('/', name: 'app_activity_log_index', methods: ['GET'])]
    public function index(ActivityLogRepository $activityLogRepository, Request $request, UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $page = $request->query->getInt('page', 1);
        $limit = 20;

        $paginator = $activityLogRepository->findPaginated($page, $limit);
        $total = $paginator->count();
        $maxPages = ceil($total / $limit);
        
        // Get today's activities
        $todayActivities = $activityLogRepository->findTodayActivities();
        
        // Get all users for filter dropdown
        $users = $userRepository->findAll();
        
        // Calculate stats
        $uniqueUsers = $activityLogRepository->countUniqueUsers();
        $mostActiveUser = $activityLogRepository->getMostActiveUser();
        
        // Get filter values from request
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $userId = $request->query->get('user_id');
        $actionType = $request->query->get('action_type');

        return $this->render('dashboard/activity_log/index.html.twig', [
            'activity_logs' => $paginator,
            'current_page' => $page,
            'max_pages' => $maxPages,
            'total_items' => $total,
            'today_activities' => $todayActivities,
            'today_activities_count' => count($todayActivities), // Changed from array to count
            'users' => $users,
            'unique_users' => $uniqueUsers,
            'most_active_user' => $mostActiveUser ? $mostActiveUser->getEmail() : 'None',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' => $userId,
            'action_type' => $actionType,
        ]);
    }

    #[Route('/my-activities', name: 'app_activity_log_my_activities', methods: ['GET'])]
    public function myActivities(ActivityLogRepository $activityLogRepository, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $page = $request->query->getInt('page', 1);
        $limit = 15;

        $paginator = $activityLogRepository->findByUserPaginated($user, $page, $limit);
        $total = $paginator->count();
        $maxPages = ceil($total / $limit);

        return $this->render('dashboard/activity_log/my_activities.html.twig', [
            'activity_logs' => $paginator,
            'current_page' => $page,
            'max_pages' => $maxPages,
            'total_items' => $total,
            'user' => $user,
        ]);
    }

    #[Route('/search', name: 'app_activity_log_search', methods: ['GET'])]
    public function search(Request $request, ActivityLogRepository $activityLogRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $query = $request->query->get('q', '');
        $page = $request->query->getInt('page', 1);
        $limit = 20;

        if (empty($query)) {
            return $this->redirectToRoute('app_activity_log_index');
        }

        $paginator = $activityLogRepository->search($query, $page, $limit);
        $total = $paginator->count();
        $maxPages = ceil($total / $limit);

        return $this->render('dashboard/activity_log/search.html.twig', [
            'activity_logs' => $paginator,
            'current_page' => $page,
            'max_pages' => $maxPages,
            'total_items' => $total,
            'query' => $query,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_log_show', methods: ['GET'])]
    public function show(int $id, ActivityLogRepository $activityLogRepository): Response
    {
        $activityLog = $activityLogRepository->find($id);
        
        if (!$activityLog) {
            throw $this->createNotFoundException('Activity log not found');
        }

        // Check permissions - admin can see all, users can only see their own
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $activityLog->getUser() !== $user) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/activity_log/show.html.twig', [
            'activity_log' => $activityLog,
        ]);
    }

    #[Route('/clear-old', name: 'app_activity_log_clear_old', methods: ['POST'])]
    public function clearOld(ActivityLogRepository $activityLogRepository, Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('clear_old_logs', $request->request->get('_token'))) {
            $days = $request->request->getInt('days', 30);
            $deletedCount = $activityLogRepository->deleteOldLogs($days);
            
            $this->addFlash('success', sprintf('Deleted %d activity logs older than %d days.', $deletedCount, $days));
        }

        return $this->redirectToRoute('app_activity_log_index');
    }

    #[Route('/dashboard-stats', name: 'app_activity_log_dashboard_stats', methods: ['GET'])]
    public function dashboardStats(ActivityLogRepository $activityLogRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER') && !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        $stats = [
            'total_today' => $activityLogRepository->countToday(),
            'total_week' => $activityLogRepository->countThisWeek(),
            'total_month' => $activityLogRepository->countThisMonth(),
            'user_activities_today' => $activityLogRepository->countUserActivitiesToday($this->getUser()),
        ];

        return $this->json($stats);
    }

    #[Route('/export', name: 'app_activity_log_export', methods: ['GET'])]
    public function export(ActivityLogRepository $activityLogRepository, Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $format = $request->query->get('format', 'csv');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        $logs = $activityLogRepository->findForExport($startDate, $endDate);

        if ($format === 'csv') {
            return $this->exportToCsv($logs);
        } elseif ($format === 'json') {
            return $this->exportToJson($logs);
        }

        $this->addFlash('error', 'Invalid export format.');
        return $this->redirectToRoute('app_activity_log_index');
    }

    private function exportToCsv(array $logs): Response
    {
        $csv = "ID,User,Action,Description,IP Address,User Agent,Route,Created At\n";
        
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%d,%s,%s,%s,%s,%s,%s,%s\n",
                $log->getId(),
                $log->getUser()->getEmail(),
                $log->getAction(),
                str_replace(',', ';', $log->getDescription() ?? ''),
                $log->getIpAddress() ?? '',
                str_replace(',', ';', $log->getUserAgent() ?? ''),
                $log->getRoute() ?? '',
                $log->getCreatedAt()->format('Y-m-d H:i:s')
            );
        }

        $response = new Response($csv);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');

        return $response;
    }

    private function exportToJson(array $logs): Response
    {
        $data = array_map(function ($log) {
            return [
                'id' => $log->getId(),
                'user' => $log->getUser()->getEmail(),
                'action' => $log->getAction(),
                'description' => $log->getDescription(),
                'ip_address' => $log->getIpAddress(),
                'user_agent' => $log->getUserAgent(),
                'route' => $log->getRoute(),
                'created_at' => $log->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $logs);

        $response = new Response(json_encode($data, JSON_PRETTY_PRINT));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Disposition', 'attachment; filename="activity_logs_' . date('Y-m-d') . '.json"');

        return $response;
    }
}