<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/users')]
class UserManagementController extends AbstractController
{
    #[Route('/', name: 'app_user_management_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/user_management/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_management_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password if provided
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            // Create UserProfile from form data
            $this->createOrUpdateUserProfile($user, $form);
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully.');

            return $this->redirectToRoute('app_user_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/user_management/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_management_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/user_management/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_management_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        User $user, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(UserFormType::class, $user, ['is_new' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password if provided
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            // Update UserProfile from form data
            $this->createOrUpdateUserProfile($user, $form);
            
            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('app_user_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/user_management/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_management_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'User deleted successfully.');
        }

        return $this->redirectToRoute('app_user_management_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Helper method to create or update UserProfile from form data
     */
    private function createOrUpdateUserProfile(User $user, $form): void
    {
        // Get UserProfile or create new one
        $userProfile = $user->getUserProfile();
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->setUser($user);
        }

        // Update UserProfile fields from form
        $userProfile->setFullName($form->get('fullName')->getData());
        $userProfile->setPhoneNumber($form->get('phoneNumber')->getData());
        $userProfile->setGender($form->get('gender')->getData());
        $userProfile->setDateOfBirth($form->get('dateOfBirth')->getData());
        $userProfile->setAddress($form->get('address')->getData());
        $userProfile->setCity($form->get('city')->getData());
        $userProfile->setState($form->get('state')->getData());
        $userProfile->setZipCode($form->get('zipCode')->getData());
        $userProfile->setCountry($form->get('country')->getData());
        $userProfile->setBio($form->get('bio')->getData());
        
        // Update timestamps
        $userProfile->setUpdatedAt(new \DateTimeImmutable());
        
        // Set the profile if not already set
        if (!$user->getUserProfile()) {
            $user->setUserProfile($userProfile);
        }
    }
}