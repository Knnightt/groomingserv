<?php

namespace App\Repository;

use App\Entity\Pet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pet>
 */
class PetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pet::class);
    }

    // Add to your existing PetRepository class:

public function getPetSpeciesStats(): array
{
    $qb = $this->createQueryBuilder('p')
        ->select('p.species, COUNT(p.id) as count')
        ->groupBy('p.species')
        ->orderBy('count', 'DESC');
    
    $results = $qb->getQuery()->getResult();
    
    $stats = [];
    foreach ($results as $result) {
        $species = $result['species'] ?? 'Other';
        $stats[$species] = (int) $result['count'];
    }
    
    return $stats;
}
}
