<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Entity\User;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/staff')]
class StaffApiController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $staffMembers = $em->getRepository(Staff::class)->findAll();
        $data = array_map(function($staff) {
            $user = $staff->getUser();
            return [
                'id' => $staff->getId(),
                'staffId' => $staff->getStaffId(),
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                ],
                'staffRole' => $staff->getStaffRole(),
                'specializations' => $staff->getSpecializations(),
                'employmentStatus' => $staff->getEmploymentStatus(),
                'hourlyRate' => $staff->getHourlyRate(),
                'experienceYears' => $staff->getExperienceYears(),
            ];
        }, $staffMembers);

        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $em, $id): JsonResponse
    {
        $staff = $em->getRepository(Staff::class)->find($id);
        if (!$staff) {
            return $this->json(['error' => 'Staff member not found'], 404);
        }

        $user = $staff->getUser();
        return $this->json([
            'id' => $staff->getId(),
            'staffId' => $staff->getStaffId(),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ],
            'staffRole' => $staff->getStaffRole(),
            'specializations' => $staff->getSpecializations(),
            'biography' => $staff->getBiography(),
            'employmentStatus' => $staff->getEmploymentStatus(),
            'hourlyRate' => $staff->getHourlyRate(),
            'experienceYears' => $staff->getExperienceYears(),
            'hireDate' => $staff->getHireDate()?->format('Y-m-d'),
            'workingDays' => $staff->getWorkingDays(),
            'startTime' => $staff->getStartTime()?->format('H:i'),
            'endTime' => $staff->getEndTime()?->format('H:i'),
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
            return $this->json(['error' => 'Email, password, and name are required'], 400);
        }

        // Create user first
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username'] ?? $data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_STAFF']); // Base role, will be enhanced by staff role
        $user->setIsVerified(true);

        // Create user profile
        $userProfile = new UserProfile();
        $userProfile->setFullName($data['name']);
        $userProfile->setPhoneNumber($data['phone'] ?? null);
        $user->setUserProfile($userProfile);

        // Create staff
        $staff = new Staff();
        $staff->setUser($user);
        $staff->setStaffId($data['staffId'] ?? 'ST' . time());
        $staff->setStaffRole($data['staffRole'] ?? Staff::ROLE_GROOMER);
        $staff->setSpecializations($data['specializations'] ?? []);
        $staff->setBiography($data['biography'] ?? null);
        $staff->setEmploymentStatus($data['employmentStatus'] ?? Staff::STATUS_ACTIVE);
        $staff->setHourlyRate($data['hourlyRate'] ?? '0.00');
        $staff->setExperienceYears($data['experienceYears'] ?? 0);
        $staff->setWorkingDays($data['workingDays'] ?? []);
        $staff->setCanHandleAggressivePets($data['canHandleAggressivePets'] ?? false);
        $staff->setIsCertified($data['isCertified'] ?? false);

        $em->persist($user);
        $em->persist($userProfile);
        $em->persist($staff);
        $em->flush();

        return $this->json([
            'id' => $staff->getId(),
            'staffId' => $staff->getStaffId(),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ],
            'staffRole' => $staff->getStaffRole(),
            'employmentStatus' => $staff->getEmploymentStatus(),
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        $staff = $em->getRepository(Staff::class)->find($id);
        if (!$staff) {
            return $this->json(['error' => 'Staff member not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $user = $staff->getUser();

        // Update user data
        if (isset($data['email'])) $user->setEmail($data['email']);
        if (isset($data['name'])) $user->getUserProfile()->setFullName($data['name']);
        if (isset($data['phone'])) $user->getUserProfile()->setPhoneNumber($data['phone']);

        // Update staff data
        if (isset($data['staffId'])) $staff->setStaffId($data['staffId']);
        if (isset($data['staffRole'])) $staff->setStaffRole($data['staffRole']);
        if (isset($data['specializations'])) $staff->setSpecializations($data['specializations']);
        if (isset($data['biography'])) $staff->setBiography($data['biography']);
        if (isset($data['employmentStatus'])) $staff->setEmploymentStatus($data['employmentStatus']);
        if (isset($data['hourlyRate'])) $staff->setHourlyRate($data['hourlyRate']);
        if (isset($data['experienceYears'])) $staff->setExperienceYears($data['experienceYears']);
        if (isset($data['workingDays'])) $staff->setWorkingDays($data['workingDays']);
        if (isset($data['canHandleAggressivePets'])) $staff->setCanHandleAggressivePets($data['canHandleAggressivePets']);
        if (isset($data['isCertified'])) $staff->setIsCertified($data['isCertified']);

        $em->flush();

        return $this->json([
            'id' => $staff->getId(),
            'staffId' => $staff->getStaffId(),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ],
            'staffRole' => $staff->getStaffRole(),
            'employmentStatus' => $staff->getEmploymentStatus(),
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, $id): JsonResponse
    {
        $staff = $em->getRepository(Staff::class)->find($id);
        if (!$staff) {
            return $this->json(['error' => 'Staff member not found'], 404);
        }

        $em->remove($staff);
        $em->flush();

        return $this->json(['status' => 'Staff member deleted']);
    }
}