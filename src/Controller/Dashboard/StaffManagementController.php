<?php

namespace App\Controller\Dashboard;

use App\Entity\Staff;
use App\Form\StaffFormType;
use App\Repository\StaffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/staff')]
class StaffManagementController extends AbstractController
{
   #[Route('/', name: 'app_staff_management_index', methods: ['GET'])]
    public function index(StaffRepository $staffRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $staffMembers = $staffRepository->findAll();
        
        // Calculate stats
        $activeStaff = 0;
        $groomerCount = 0;
        $totalExperience = 0;
        
        foreach ($staffMembers as $staff) {
            if ($staff->getEmploymentStatus() === 'Active') {
                $activeStaff++;
            }
            
            if ($staff->getStaffRole() === 'ROLE_GROOMER' || $staff->getStaffRole() === 'Groomer') {
                $groomerCount++;
            }
            
            $totalExperience += $staff->getExperienceYears() ?? 0;
        }
        
        $avgExperience = count($staffMembers) > 0 ? round($totalExperience / count($staffMembers), 1) : 0;

        return $this->render('dashboard/staff_management/index.html.twig', [
            'staff_members' => $staffMembers, // Change from 'staff' to 'staff_members'
            'active_staff' => $activeStaff,
            'groomer_count' => $groomerCount,
            'avg_experience' => $avgExperience,
        ]);
    }

    #[Route('/new', name: 'app_staff_management_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $staff = new Staff();
        $form = $this->createForm(StaffFormType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($staff);
            $entityManager->flush();

            $this->addFlash('success', 'Staff member created successfully.');

            return $this->redirectToRoute('app_staff_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/staff_management/new.html.twig', [
            'staff' => $staff,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_staff_management_show', methods: ['GET'])]
    public function show(Staff $staff): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/staff_management/show.html.twig', [
            'staff' => $staff,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_staff_management_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(StaffFormType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Staff member updated successfully.');

            return $this->redirectToRoute('app_staff_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/staff_management/edit.html.twig', [
            'staff' => $staff,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/deactivate', name: 'app_staff_management_deactivate', methods: ['POST'])]
    public function deactivate(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('deactivate'.$staff->getId(), $request->request->get('_token'))) {
            $staff->setEmploymentStatus('Inactive');
            $entityManager->flush();
            
            $this->addFlash('success', 'Staff member deactivated successfully.');
        }

        return $this->redirectToRoute('app_staff_management_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate', name: 'app_staff_management_activate', methods: ['POST'])]
    public function activate(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('activate'.$staff->getId(), $request->request->get('_token'))) {
            $staff->setEmploymentStatus('Active');
            $entityManager->flush();
            
            $this->addFlash('success', 'Staff member activated successfully.');
        }

        return $this->redirectToRoute('app_staff_management_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_staff_management_delete', methods: ['POST'])]
    public function delete(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$staff->getId(), $request->request->get('_token'))) {
            $entityManager->remove($staff);
            $entityManager->flush();
            
            $this->addFlash('success', 'Staff member deleted successfully.');
        }

        return $this->redirectToRoute('app_staff_management_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/schedule', name: 'app_staff_management_schedule', methods: ['GET'])]
    public function schedule(Staff $staff): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/staff_management/schedule.html.twig', [
            'staff' => $staff,
        ]);
    }
}