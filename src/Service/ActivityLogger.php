<?php

namespace App\Service;

use App\Entity\ActivityLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class ActivityLogger
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $entityManager, Security $security, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function log(string $action, ?string $description = null): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $ip = $request ? $request->getClientIp() : null;
        $userAgent = $request ? $request->headers->get('User-Agent') : null;
        $route = $request ? $request->attributes->get('_route') : null;

        $log = new ActivityLog();
        $log->setUser($user)
            ->setAction($action)
            ->setDescription($description)
            ->setIpAddress($ip)
            ->setUserAgent($userAgent)
            ->setRoute($route);

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
