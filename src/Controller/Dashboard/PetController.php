<?php

namespace App\Controller\Dashboard;

use App\Entity\Pet;
use App\Form\PetFormType;
use App\Repository\PetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/pets')]
class PetController extends AbstractController
{
    #[Route('/', name: 'app_pet_index', methods: ['GET'])]
    public function index(PetRepository $petRepository): Response
    {
        $user = $this->getUser();
        
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            $pets = $petRepository->findAll();
        } elseif ($this->isGranted('ROLE_STAFF')) {
            $pets = $petRepository->findAll(); // Staff can view all pets
        } else {
            $pets = $petRepository->findBy(['owner' => $user]);
        }

        // Calculate stats
        $dogCount = 0;
        $catCount = 0;
        $otherCount = 0;
        $activePets = 0;
        $totalAge = 0;
        
        foreach ($pets as $pet) {
            // Count by species
            $species = strtolower($pet->getSpecies() ?? '');
            if (str_contains($species, 'dog')) {
                $dogCount++;
            } elseif (str_contains($species, 'cat')) {
                $catCount++;
            } else {
                $otherCount++;
            }
            
            // Count active pets
            if ($pet->isActive()) {
                $activePets++;
            }
            
            // Calculate total age
            $totalAge += $pet->getAge() ?? 0;
        }
        
        $avgAge = count($pets) > 0 ? round($totalAge / count($pets), 1) : 0;

        return $this->render('dashboard/pet/index.html.twig', [
            'pets' => $pets,
            'dog_count' => $dogCount,
            'cat_count' => $catCount,
            'other_pet_count' => $otherCount,
            'total_pets' => count($pets),
            'active_pets' => $activePets,
            'avg_age' => $avgAge,
        ]);
    }

    #[Route('/new', name: 'app_pet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        // Allow admin, manager, and regular users to add pets
        // Staff cannot add pets
        if (!$this->isGranted('ROLE_STAFF') && !$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            $this->addFlash('warning', 'Staff members cannot add pets.');
            return $this->redirectToRoute('app_pet_index');
        }

        $pet = new Pet();
        
        // Set owner: all pets are owned by the creator
        $pet->setOwner($user);
        
        // Create form WITHOUT options
        $form = $this->createForm(PetFormType::class, $pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pet);
            $entityManager->flush();

            $this->addFlash('success', 'Pet added successfully.');

            return $this->redirectToRoute('app_pet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/pet/new.html.twig', [
            'pet' => $pet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_pet_show', methods: ['GET'])]
    public function show(Pet $pet): Response
    {
        // Check permissions
        $user = $this->getUser();
        if ($pet->getOwner() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER') && 
            !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/pet/show.html.twig', [
            'pet' => $pet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pet $pet, EntityManagerInterface $entityManager): Response
    {
        // Check permissions
        $user = $this->getUser();
        if ($pet->getOwner() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_STAFF') && 
            !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(PetFormType::class, $pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Pet updated successfully.');

            return $this->redirectToRoute('app_pet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/pet/edit.html.twig', [
            'pet' => $pet,
            'form' => $form->createView(), // Fixed: add ->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_pet_delete', methods: ['POST'])]
    public function delete(Request $request, Pet $pet, EntityManagerInterface $entityManager): Response
    {
        // Check permissions
        $user = $this->getUser();
        if ($pet->getOwner() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$pet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pet);
            $entityManager->flush();
            
            $this->addFlash('success', 'Pet deleted successfully.');
        }

        return $this->redirectToRoute('app_pet_index', [], Response::HTTP_SEE_OTHER);
    }
}