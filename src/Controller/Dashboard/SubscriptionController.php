<?php

namespace App\Controller\Dashboard;

use App\Entity\Subscription;
use App\Form\SubscriptionFormType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/dashboard/subscriptions')]
class SubscriptionController extends AbstractController
{
    #[Route('/', name: 'app_subscription_index', methods: ['GET'])]
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        $user = $this->getUser();
        
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            $subscriptions = $subscriptionRepository->findAll();
        } elseif ($this->isGranted('ROLE_STAFF')) {
            $subscriptions = $subscriptionRepository->findAll(); // Staff can view all
        } else {
            $subscriptions = $subscriptionRepository->findBy(['user' => $user]);
        }

        return $this->render('dashboard/subscription/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }

    #[Route('/plans', name: 'app_subscription_plans', methods: ['GET'])]
    public function plans(): Response
    {
        $plans = [
            [
                'name' => 'Basic',
                'price' => 29.99,
                'features' => ['Monthly grooming', '10% discount', 'Priority booking'],
                'popular' => false,
            ],
            [
                'name' => 'Premium',
                'price' => 59.99,
                'features' => ['Weekly grooming', '20% discount', 'Priority booking', 'Free nail trim'],
                'popular' => true,
            ],
            [
                'name' => 'Pro',
                'price' => 99.99,
                'features' => ['Unlimited grooming', '30% discount', 'Priority booking', 'Free nail trim', 'Free teeth cleaning'],
                'popular' => false,
            ],
        ];

        return $this->render('dashboard/subscription/plans.html.twig', [
            'plans' => $plans,
        ]);
    }

    #[Route('/new', name: 'app_subscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subscription);
            $entityManager->flush();

            $this->addFlash('success', 'Subscription created successfully.');

            return $this->redirectToRoute('app_subscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/subscription/new.html.twig', [
            'subscription' => $subscription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subscription_show', methods: ['GET'])]
    public function show(Subscription $subscription): Response
    {
        // Check permissions
        $user = $this->getUser();
        if ($subscription->getUser() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER') && 
            !$this->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        return $this->render('dashboard/subscription/show.html.twig', [
            'subscription' => $subscription,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subscription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subscription $subscription, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Subscription updated successfully.');

            return $this->redirectToRoute('app_subscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/subscription/edit.html.twig', [
            'subscription' => $subscription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/cancel', name: 'app_subscription_cancel', methods: ['POST'])]
    public function cancel(Request $request, Subscription $subscription, EntityManagerInterface $entityManager): Response
    {
        // Check permissions - users can cancel their own subscriptions
        $user = $this->getUser();
        if ($subscription->getUser() !== $user && 
            !$this->isGranted('ROLE_ADMIN') && 
            !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('cancel'.$subscription->getId(), $request->request->get('_token'))) {
            $subscription->setStatus('cancelled');
            $entityManager->flush();
            
            $this->addFlash('success', 'Subscription cancelled successfully.');
        }

        return $this->redirectToRoute('app_subscription_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/activate', name: 'app_subscription_activate', methods: ['POST'])]
    public function activate(Request $request, Subscription $subscription, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('activate'.$subscription->getId(), $request->request->get('_token'))) {
            $subscription->setStatus('active');
            $entityManager->flush();
            
            $this->addFlash('success', 'Subscription activated successfully.');
        }

        return $this->redirectToRoute('app_subscription_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_subscription_delete', methods: ['POST'])]
    public function delete(Request $request, Subscription $subscription, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$subscription->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subscription);
            $entityManager->flush();
            
            $this->addFlash('success', 'Subscription deleted successfully.');
        }

        return $this->redirectToRoute('app_subscription_index', [], Response::HTTP_SEE_OTHER);
    }
}