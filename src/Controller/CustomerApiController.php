<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api/customers')]
class CustomerApiController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $customers = $em->getRepository(User::class)->findBy(['roles' => ['ROLE_USER']]);
        $data = array_map(function($user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
            ];
        }, $customers);
        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user || !in_array('ROLE_USER', $user->getRoles())) {
            return $this->json(['error' => 'Customer not found'], 404);
        }
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ]);
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email'] ?? '');
        $user->setName($data['name'] ?? '');
        $user->setRoles(['ROLE_USER']);
        // Set password, validation, etc. as needed
        $em->persist($user);
        $em->flush();
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ], 201);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user || !in_array('ROLE_USER', $user->getRoles())) {
            return $this->json(['error' => 'Customer not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setName($data['name'] ?? $user->getName());
        $em->flush();
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ]);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $em, $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user || !in_array('ROLE_USER', $user->getRoles())) {
            return $this->json(['error' => 'Customer not found'], 404);
        }
        $em->remove($user);
        $em->flush();
        return $this->json(['status' => 'Customer deleted']);
    }
}
