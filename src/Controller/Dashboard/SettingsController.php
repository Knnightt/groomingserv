<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\ProfileFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/dashboard/settings')]
class SettingsController extends AbstractController
{
    #[Route('/', name: 'app_settings_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('dashboard/settings/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile', name: 'app_settings_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userProfile = $user->getUserProfile();
        if (!$userProfile) {
            $userProfile = new UserProfile();
            $userProfile->setUser($user);
            $user->setUserProfile($userProfile);
            $entityManager->persist($userProfile);
        }

        $form = $this->createForm(ProfileFormType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated successfully.');

            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('dashboard/settings/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/change-password', name: 'app_settings_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            // Check if current password is correct
            if (!$userPasswordHasher->isPasswordValid($user, $data['currentPassword'])) {
                $this->addFlash('error', 'Your current password is incorrect.');
                return $this->redirectToRoute('app_settings_change_password');
            }

            // Check if new password is different from current
            if ($data['newPassword'] === $data['currentPassword']) {
                $this->addFlash('error', 'New password must be different from current password.');
                return $this->redirectToRoute('app_settings_change_password');
            }

            // Basic password strength check
            if (strlen($data['newPassword']) < 8) {
                $this->addFlash('error', 'Password must be at least 8 characters long.');
                return $this->redirectToRoute('app_settings_change_password');
            }

            // Set new password
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $data['newPassword'])
            );

            $entityManager->flush();

            $this->addFlash('success', 'Your password has been changed successfully.');

            return $this->redirectToRoute('app_settings_index');
        }

        return $this->render('dashboard/settings/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/security', name: 'app_settings_security', methods: ['GET'])]
    public function security(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Get login history - you can replace this with actual database query
        // Example if you have a LoginHistory entity:
        // $loginHistory = $entityManager->getRepository(LoginHistory::class)
        //     ->findBy(['user' => $user], ['loginAt' => 'DESC'], 10);
        
        $loginHistory = [
            [
                'date' => (new \DateTime('-1 day'))->format('Y-m-d H:i'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '192.168.1.1',
                'device' => $this->getDeviceInfo(),
                'location' => 'Unknown'
            ],
            [
                'date' => (new \DateTime('-3 days'))->format('Y-m-d H:i'),
                'ip' => '192.168.1.100',
                'device' => 'Mobile Device',
                'location' => 'New York, US'
            ],
            [
                'date' => (new \DateTime('-1 week'))->format('Y-m-d H:i'),
                'ip' => '192.168.1.200',
                'device' => 'Desktop Computer',
                'location' => 'London, UK'
            ],
        ];

        return $this->render('dashboard/settings/security.html.twig', [
            'login_history' => $loginHistory,
            'user' => $user,
        ]);
    }

    #[Route('/billing', name: 'app_settings_billing', methods: ['GET'])]
    public function billing(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Get billing history - you can replace this with actual database query
        // Example if you have a BillingHistory entity:
        // $billingHistory = $entityManager->getRepository(BillingHistory::class)
        //     ->findBy(['user' => $user], ['date' => 'DESC'], 10);
        
        $billingHistory = [
            [
                'date' => (new \DateTime('first day of this month'))->format('Y-m-d'),
                'description' => 'Monthly Subscription',
                'amount' => '$29.99',
                'status' => 'Paid',
                'invoice' => 'INV-' . date('Ym') . '-001'
            ],
            [
                'date' => (new \DateTime('first day of last month'))->format('Y-m-d'),
                'description' => 'Monthly Subscription',
                'amount' => '$29.99',
                'status' => 'Paid',
                'invoice' => 'INV-' . date('Ym', strtotime('-1 month')) . '-001'
            ],
            [
                'date' => (new \DateTime('first day of -2 months'))->format('Y-m-d'),
                'description' => 'Monthly Subscription',
                'amount' => '$29.99',
                'status' => 'Paid',
                'invoice' => 'INV-' . date('Ym', strtotime('-2 months')) . '-001'
            ],
        ];

        // If you have subscription info, add it here
        $currentPlan = [
            'name' => 'Premium Plan',
            'price' => '$29.99/month',
            'billing_cycle' => 'Monthly',
            'next_billing_date' => (new \DateTime('first day of next month'))->format('Y-m-d'),
            'status' => 'Active'
        ];

        return $this->render('dashboard/settings/billing.html.twig', [
            'billing_history' => $billingHistory,
            'current_plan' => $currentPlan,
            'user' => $user,
        ]);
    }

    #[Route('/delete-account', name: 'app_settings_delete_account', methods: ['GET', 'POST'])]
    public function deleteAccount(
        Request $request, 
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            // For security, you might want to verify password here
            // $password = $request->request->get('password');
            // $passwordHasher = $this->container->get(UserPasswordHasherInterface::class);
            // if (!$passwordHasher->isPasswordValid($user, $password)) {
            //     $this->addFlash('error', 'Incorrect password.');
            //     return $this->redirectToRoute('app_settings_delete_account');
            // }
            
            // Check confirmation
            $confirmation = $request->request->get('confirmation');
            if ($confirmation !== 'DELETE') {
                $this->addFlash('error', 'Please type DELETE to confirm account deletion.');
                return $this->redirectToRoute('app_settings_delete_account');
            }

            // Soft delete (recommended) - mark as inactive
            $user->setIsActive(false);
            $user->setDeletedAt(new \DateTime());
            
            // Or hard delete (use carefully):
            // $entityManager->remove($user);
            
            $entityManager->flush();

            // Logout user
            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();
            
            $this->addFlash('success', 'Your account has been deleted successfully.');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('dashboard/settings/delete_account.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Helper method to get device info from user agent
     */
    private function getDeviceInfo(): string
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        if (stripos($userAgent, 'mobile') !== false) {
            if (stripos($userAgent, 'android') !== false) {
                return 'Android Device';
            } elseif (stripos($userAgent, 'iphone') !== false || stripos($userAgent, 'ipad') !== false) {
                return 'iOS Device';
            }
            return 'Mobile Device';
        }
        
        if (stripos($userAgent, 'windows') !== false) {
            return 'Windows PC';
        } elseif (stripos($userAgent, 'mac') !== false) {
            return 'Mac Computer';
        } elseif (stripos($userAgent, 'linux') !== false) {
            return 'Linux Computer';
        }
        
        return 'Desktop Computer';
    }
}