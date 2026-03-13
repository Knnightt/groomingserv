<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

  // Add to your existing UserRepository class:

public function getMonthlyUserGrowth(): array
{
    $growthData = [];
    
    // Get last 6 months (including current month)
    for ($i = 5; $i >= 0; $i--) {
        $date = new \DateTimeImmutable("first day of -{$i} months");
        $endDate = $date->modify('last day of this month 23:59:59');
        
        $result = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.createdAt <= :endDate')
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
        
        $growthData[$date->format('M Y')] = (int) $result;
    }
    
    return $growthData;
}
}
