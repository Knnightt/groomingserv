<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users')]
class UserManagementApiController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        // Get all users except customers (ROLE_USER only)
        $users = $em->getRepository(User::class)->findAll();
        $managersAndAdmins = array_filter($users, function($user) {
            $roles = $user->getRoles();
            return in_array('ROLE_MANAGER', $roles) ||
                   in_array('ROLE_ADMIN', $roles) ||
                   in_array('ROLE_SUPER_ADMIN', $roles);
        });

        $data = array_map(function($user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'roles' => $user->getRoles(),
                'isVerified' => $user->isVerified(),
                'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }, $managersAndAdmins);

        return $this->json(array_values($data));
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $roles = $user->getRoles();
        $isManagerOrAdmin = in_array('ROLE_MANAGER', $roles) ||
                           in_array('ROLE_ADMIN', $roles) ||
                           in_array('ROLE_SUPER_ADMIN', $roles);

        if (!$isManagerOrAdmin) {
            return $this->json(['error' => 'User is not a manager or admin'], 404);
        }

        $profile = $user->getUserProfile();
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
            'isVerified' => $user->isVerified(),
            'profile' => $profile ? [
                'fullName' => $profile->getFullName(),
                'phoneNumber' => $profile->getPhoneNumber(),
                'address' => $profile->getAddress(),
                'city' => $profile->getCity(),
                'state' => $profile->getState(),
                'zipCode' => $profile->getZipCode(),
                'country' => $profile->getCountry(),
            ] : null,
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updatedAt' => $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password']) || !isset($data['roles'])) {
            return $this->json(['error' => 'Email, password, and roles are required'], 400);
        }

        // Validate roles
        $allowedRoles = ['ROLE_MANAGER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'];
        $requestedRoles = $data['roles'];
        $invalidRoles = array_diff($requestedRoles, $allowedRoles);
        if (!empty($invalidRoles)) {
            return $this->json(['error' => 'Invalid roles: ' . implode(', ', $invalidRoles)], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username'] ?? $data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles($requestedRoles);
        $user->setIsVerified(true);

        // Create user profile
        $userProfile = new UserProfile();
        $userProfile->setFullName($data['name'] ?? '');
        $userProfile->setPhoneNumber($data['phone'] ?? null);
        $userProfile->setAddress($data['address'] ?? null);
        $userProfile->setCity($data['city'] ?? null);
        $userProfile->setState($data['state'] ?? null);
        $userProfile->setZipCode($data['zipCode'] ?? null);
        $userProfile->setCountry($data['country'] ?? null);
        $user->setUserProfile($userProfile);

        $em->persist($user);
        $em->persist($userProfile);
        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
            'isVerified' => $user->isVerified(),
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Update basic user data
        if (isset($data['email'])) $user->setEmail($data['email']);
        if (isset($data['username'])) $user->setUsername($data['username']);

        // Update roles if provided
        if (isset($data['roles'])) {
            $allowedRoles = ['ROLE_MANAGER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'];
            $invalidRoles = array_diff($data['roles'], $allowedRoles);
            if (!empty($invalidRoles)) {
                return $this->json(['error' => 'Invalid roles: ' . implode(', ', $invalidRoles)], 400);
            }
            $user->setRoles($data['roles']);
        }

        // Update profile data
        $profile = $user->getUserProfile();
        if ($profile) {
            if (isset($data['name'])) $profile->setFullName($data['name']);
            if (isset($data['phone'])) $profile->setPhoneNumber($data['phone']);
            if (isset($data['address'])) $profile->setAddress($data['address']);
            if (isset($data['city'])) $profile->setCity($data['city']);
            if (isset($data['state'])) $profile->setState($data['state']);
            if (isset($data['zipCode'])) $profile->setZipCode($data['zipCode']);
            if (isset($data['country'])) $profile->setCountry($data['country']);
        }

        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
            'isVerified' => $user->isVerified(),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $roles = $user->getRoles();
        $isManagerOrAdmin = in_array('ROLE_MANAGER', $roles) ||
                           in_array('ROLE_ADMIN', $roles) ||
                           in_array('ROLE_SUPER_ADMIN', $roles);

        if (!$isManagerOrAdmin) {
            return $this->json(['error' => 'User is not a manager or admin'], 404);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['status' => 'User deleted']);
    }
}