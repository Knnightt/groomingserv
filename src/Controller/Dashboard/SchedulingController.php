<?php

namespace App\Controller\Dashboard;

use App\Entity\Appointment;
use App\Form\AppointmentFormType;
use App\Repository\AppointmentRepository;
use App\Repository\PetRepository;
use App\Repository\ServiceRepository;
use App\Repository\StaffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/scheduling')]
class SchedulingController extends AbstractController
{
    #[Route('/', name: 'app_scheduling_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository, Request $request): Response
    {
        $user = $this->getUser();
        $today = new \DateTime();
        $todayStart = (clone $today)->setTime(0, 0, 0);
        $todayEnd = (clone $today)->setTime(23, 59, 59);
        
        // Get filter parameters
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $status = $request->query->get('status');
        
        // Calculate this week range
        $weekStart = (clone $today)->modify('this week')->setTime(0, 0, 0);
        $weekEnd = (clone $weekStart)->modify('+6 days')->setTime(23, 59, 59);
        
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            // Admin/Manager sees all appointments
            $queryBuilder = $appointmentRepository->createQueryBuilder('a');
            
            // Apply filters
            if ($startDate) {
                $queryBuilder->andWhere('a.startAt >= :startDate')
                    ->setParameter('startDate', $startDate . ' 00:00:00');
            }
            if ($endDate) {
                $queryBuilder->andWhere('a.startAt <= :endDate')
                    ->setParameter('endDate', $endDate . ' 23:59:59');
            }
            if ($status) {
                $queryBuilder->andWhere('a.status = :status')
                    ->setParameter('status', $status);
            }
            
            $appointments = $queryBuilder->orderBy('a.startAt', 'DESC')->getQuery()->getResult();
            
            // Get today's appointments
            $todayAppointments = $appointmentRepository->createQueryBuilder('a')
                ->where('a.startAt >= :todayStart')
                ->andWhere('a.startAt <= :todayEnd')
                ->setParameter('todayStart', $todayStart)
                ->setParameter('todayEnd', $todayEnd)
                ->getQuery()
                ->getResult();
                
            // Get this week's appointments
            $thisWeekAppointments = $appointmentRepository->createQueryBuilder('a')
                ->where('a.startAt >= :weekStart')
                ->andWhere('a.startAt <= :weekEnd')
                ->setParameter('weekStart', $weekStart)
                ->setParameter('weekEnd', $weekEnd)
                ->getQuery()
                ->getResult();
                
            // Calculate today's revenue
            $todayRevenue = $appointmentRepository->createQueryBuilder('a')
                ->select('SUM(a.amount)')
                ->where('a.startAt >= :todayStart')
                ->andWhere('a.startAt <= :todayEnd')
                ->andWhere('a.isPaid = :paid')
                ->setParameter('todayStart', $todayStart)
                ->setParameter('todayEnd', $todayEnd)
                ->setParameter('paid', true)
                ->getQuery()
                ->getSingleScalarResult() ?? 0;
                
        } elseif ($this->isGranted('ROLE_STAFF')) {
            // Staff sees their appointments
            $staff = $user->getStaff();
            $queryBuilder = $appointmentRepository->createQueryBuilder('a')
                ->where('a.assignedStaff = :staff');
            
            // Apply filters
            if ($startDate) {
                $queryBuilder->andWhere('a.startAt >= :startDate')
                    ->setParameter('startDate', $startDate . ' 00:00:00');
            }
            if ($endDate) {
                $queryBuilder->andWhere('a.startAt <= :endDate')
                    ->setParameter('endDate', $endDate . ' 23:59:59');
            }
            if ($status) {
                $queryBuilder->andWhere('a.status = :status')
                    ->setParameter('status', $status);
            }
            
            $appointments = $queryBuilder
                ->setParameter('staff', $staff)
                ->orderBy('a.startAt', 'DESC')
                ->getQuery()
                ->getResult();
            
            // Get today's appointments for this staff
            $todayAppointments = $appointmentRepository->createQueryBuilder('a')
                ->where('a.assignedStaff = :staff')
                ->andWhere('a.startAt >= :todayStart')
                ->andWhere('a.startAt <= :todayEnd')
                ->setParameter('staff', $staff)
                ->setParameter('todayStart', $todayStart)
                ->setParameter('todayEnd', $todayEnd)
                ->getQuery()
                ->getResult();
                
            // Get this week's appointments for this staff
            $thisWeekAppointments = $appointmentRepository->createQueryBuilder('a')
                ->where('a.assignedStaff = :staff')
                ->andWhere('a.startAt >= :weekStart')
                ->andWhere('a.startAt <= :weekEnd')
                ->setParameter('staff', $staff)
                ->setParameter('weekStart', $weekStart)
                ->setParameter('weekEnd', $weekEnd)
                ->getQuery()
                ->getResult();
                
            // Calculate today's revenue for this staff
            $todayRevenue = $appointmentRepository->createQueryBuilder('a')
                ->select('SUM(a.amount)')
                ->where('a.assignedStaff = :staff')
                ->andWhere('a.startAt >= :todayStart')
                ->andWhere('a.startAt <= :todayEnd')
                ->andWhere('a.isPaid = :paid')
                ->setParameter('staff', $staff)
                ->setParameter('todayStart', $todayStart)
                ->setParameter('todayEnd', $todayEnd)
                ->setParameter('paid', true)
                ->getQuery()
                ->getSingleScalarResult() ?? 0;
                
        } else {
            // Regular users see their own appointments
            $queryBuilder = $appointmentRepository->createQueryBuilder('a')
                ->where('a.customer = :customer');
            
            // Apply filters
            if ($startDate) {
                $queryBuilder->andWhere('a.startAt >= :startDate')
                    ->setParameter('startDate', $startDate . ' 00:00:00');
            }
            if ($endDate) {
                $queryBuilder->andWhere('a.startAt <= :endDate')
                    ->setParameter('endDate', $endDate . ' 23:59:59');
            }
            if ($status) {
                $queryBuilder->andWhere('a.status = :status')
                    ->setParameter('status', $status);
            }
            
            $appointments = $queryBuilder
                ->setParameter('customer', $user)
                ->orderBy('a.startAt', 'DESC')
                ->getQuery()
                ->getResult();
            
            // Get today's appointments for this user
            $todayAppointments = $appointmentRepository->createQueryBuilder('a')
                ->where('a.customer = :customer')
                ->andWhere('a.startAt >= :todayStart')
                ->andWhere('a.startAt <= :todayEnd')
                ->setParameter('customer', $user)
                ->setParameter('todayStart', $todayStart)
                ->setParameter('todayEnd', $todayEnd)
                ->getQuery()
                ->getResult();
                
            // Get this week's appointments for this user
            $thisWeekAppointments = $appointmentRepository->createQueryBuilder('a')
                ->where('a.customer = :customer')
                ->andWhere('a.startAt >= :weekStart')
                ->andWhere('a.startAt <= :weekEnd')
                ->setParameter('customer', $user)
                ->setParameter('weekStart', $weekStart)
                ->setParameter('weekEnd', $weekEnd)
                ->getQuery()
                ->getResult();
                
            // Calculate today's revenue for this user
            $todayRevenue = $appointmentRepository->createQueryBuilder('a')
                ->select('SUM(a.amount)')
                ->where('a.customer = :customer')
                ->andWhere('a.startAt >= :todayStart')
                ->andWhere('a.startAt <= :todayEnd')
                ->andWhere('a.isPaid = :paid')
                ->setParameter('customer', $user)
                ->setParameter('todayStart', $todayStart)
                ->setParameter('todayEnd', $todayEnd)
                ->setParameter('paid', true)
                ->getQuery()
                ->getSingleScalarResult() ?? 0;
        }

        // Calculate stats
        $pendingAppointments = array_filter($appointments, fn($a) => $a->getStatus() === 'Pending');
        $pendingAppointments = count($pendingAppointments);

        return $this->render('dashboard/scheduling/index.html.twig', [
            'appointments' => $appointments,
            'today_appointments' => count($todayAppointments),
            'pending_appointments' => $pendingAppointments,
            'this_week_appointments' => count($thisWeekAppointments),
            'today_revenue' => $todayRevenue,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
        ]);
    }

  


    #[Route('/calendar', name: 'app_scheduling_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER') && !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/scheduling/calendar.html.twig');
    }

    #[Route('/new', name: 'app_scheduling_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        PetRepository $petRepository,
        ServiceRepository $serviceRepository,
        StaffRepository $staffRepository
    ): Response {
        $appointment = new Appointment();
        
        // Set customer to current user for regular users
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER') && !$this->isGranted('ROLE_STAFF')) {
            $appointment->setCustomer($this->getUser());
        }

        $form = $this->createForm(AppointmentFormType::class, $appointment, [
            'user' => $this->getUser(),
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If user is regular user, set their pet if they have one
            if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER') && !$this->isGranted('ROLE_STAFF')) {
                $userPets = $petRepository->findBy(['owner' => $this->getUser()]);
                if (!empty($userPets)) {
                    $appointment->setPet($userPets[0]);
                }
                $appointment->setCustomer($this->getUser());
            }

            $entityManager->persist($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Appointment scheduled successfully.');

            return $this->redirectToRoute('app_scheduling_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/scheduling/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
            'pets' => $this->isGranted('ROLE_USER') ? $petRepository->findBy(['owner' => $this->getUser()]) : [],
            'services' => $serviceRepository->findBy(['isActive' => true]),
            'staff' => $staffRepository->findBy(['employmentStatus' => 'Active']),
        ]);
    }

    #[Route('/{id}', name: 'app_scheduling_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        // Check if user has permission to view this appointment
        $user = $this->getUser();
        if ($appointment->getCustomer() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER') && 
            !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/scheduling/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_scheduling_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Appointment $appointment, 
        EntityManagerInterface $entityManager
    ): Response {
        // Check permissions
        $user = $this->getUser();
        if ($appointment->getCustomer() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER') && 
            !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(AppointmentFormType::class, $appointment, [
            'user' => $user,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Appointment updated successfully.');

            return $this->redirectToRoute('app_scheduling_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/scheduling/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/complete', name: 'app_scheduling_complete', methods: ['POST'])]
    public function complete(
        Request $request, 
        Appointment $appointment, 
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER') && !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('complete'.$appointment->getId(), $request->request->get('_token'))) {
            $appointment->setStatus('Completed');
            $appointment->setIsPaid(true);
            $entityManager->flush();
            
            $this->addFlash('success', 'Appointment marked as completed.');
        }

        return $this->redirectToRoute('app_scheduling_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/cancel', name: 'app_scheduling_cancel', methods: ['POST'])]
    public function cancel(
        Request $request, 
        Appointment $appointment, 
        EntityManagerInterface $entityManager
    ): Response {
        // Check permissions - users can cancel their own appointments
        $user = $this->getUser();
        if ($appointment->getCustomer() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER') && 
            !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('cancel'.$appointment->getId(), $request->request->get('_token'))) {
            $appointment->setStatus('Cancelled');
            $entityManager->flush();
            
            $this->addFlash('success', 'Appointment cancelled successfully.');
        }

        return $this->redirectToRoute('app_scheduling_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_scheduling_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Appointment $appointment, 
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
            
            $this->addFlash('success', 'Appointment deleted successfully.');
        }

        return $this->redirectToRoute('app_scheduling_index', [], Response::HTTP_SEE_OTHER);
    }
}