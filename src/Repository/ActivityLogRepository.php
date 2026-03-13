<?php

namespace App\Repository;

use App\Entity\ActivityLog;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityLog>
 */
class ActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityLog::class);
    }

    public function findPaginated(int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();
        
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        
        return $paginator;
    }

    public function findByUserPaginated(User $user, int $page, int $limit): Paginator
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery();
        
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        
        return $paginator;
    }

    public function findRecentActivities(int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getUserActivities(User $user, int $limit = 20): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function search(string $query, int $page, int $limit): Paginator
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->where('a.action LIKE :search')
            ->orWhere('a.description LIKE :search')
            ->orWhere('a.ipAddress LIKE :search')
            ->orWhere('a.route LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->setParameter('search', '%' . $query . '%')
            ->orderBy('a.createdAt', 'DESC');
        
        $paginator = new Paginator($qb->getQuery());
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        
        return $paginator;
    }

    public function countToday(): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->modify('+1 day');
        
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.createdAt >= :start')
            ->andWhere('a.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countThisWeek(): int
    {
        $start = new \DateTimeImmutable('monday this week');
        $end = $start->modify('+1 week');
        
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.createdAt >= :start')
            ->andWhere('a.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countThisMonth(): int
    {
        $start = new \DateTimeImmutable('first day of this month');
        $end = $start->modify('+1 month');
        
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.createdAt >= :start')
            ->andWhere('a.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUserActivitiesToday(User $user): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->modify('+1 day');
        
        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.user = :user')
            ->andWhere('a.createdAt >= :start')
            ->andWhere('a.createdAt < :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function deleteOldLogs(int $days): int
    {
        $cutoffDate = new \DateTimeImmutable("-{$days} days");
        
        $qb = $this->createQueryBuilder('a')
            ->delete()
            ->where('a.createdAt < :cutoffDate')
            ->setParameter('cutoffDate', $cutoffDate);
        
        return $qb->getQuery()->execute();
    }

    public function findForExport(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC');
        
        if ($startDate) {
            $qb->andWhere('a.createdAt >= :start')
               ->setParameter('start', $startDate);
        }
        
        if ($endDate) {
            $endDate = $endDate->setTime(23, 59, 59);
            $qb->andWhere('a.createdAt <= :end')
               ->setParameter('end', $endDate);
        }
        
        return $qb->getQuery()->getResult();
    }

    public function findTodayActivities(): array
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->modify('+1 day');
        
        return $this->createQueryBuilder('a')
            ->where('a.createdAt >= :start')
            ->andWhere('a.createdAt < :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    public function countUniqueUsers(): int
{
    return (int) $this->createQueryBuilder('a')
        ->select('COUNT(DISTINCT a.user)')
        ->getQuery()
        ->getSingleScalarResult();
}

public function getMostActiveUser(): ?User
{
    $qb = $this->createQueryBuilder('a')
        ->select('u.id, COUNT(a.id) as activity_count')
        ->innerJoin('a.user', 'u')
        ->groupBy('u.id')
        ->orderBy('activity_count', 'DESC')
        ->setMaxResults(1);
    
    $result = $qb->getQuery()->getOneOrNullResult();
    
    if ($result && isset($result['id'])) {
        return $this->getEntityManager()->getRepository(User::class)->find($result['id']);
    }
    
    return null;
}
}