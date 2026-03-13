<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function findRecentAppointments(int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.startAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTodayRevenue(): float
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('a')
            ->select('SUM(a.amount - COALESCE(a.discount, 0) + COALESCE(a.tax, 0)) as revenue')
            ->where('a.startAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery();

        $result = $qb->getSingleScalarResult();
        return $result !== null ? (float) $result : 0.0;
    }

    public function countTodayAppointments(): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.startAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countCompletedToday(): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.status = :status')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('status', Appointment::STATUS_COMPLETED)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTodayAppointments(): array
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return $this->createQueryBuilder('a')
            ->where('a.startAt BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('a.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countStaffAppointmentsToday($staff): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.assignedStaff = :staff')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('staff', $staff)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countStaffCompletedToday($staff): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.assignedStaff = :staff')
            ->andWhere('a.status = :status')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('staff', $staff)
            ->setParameter('status', Appointment::STATUS_COMPLETED)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countStaffPendingToday($staff): int
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.assignedStaff = :staff')
            ->andWhere('a.status = :status')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('staff', $staff)
            ->setParameter('status', Appointment::STATUS_PENDING)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findStaffAppointmentsToday($staff): array
    {
        $start = new \DateTimeImmutable('today');
        $end = $start->setTime(23, 59, 59);

        return $this->createQueryBuilder('a')
            ->where('a.assignedStaff = :staff')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('staff', $staff)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('a.startAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countUpcomingUserAppointments($user): int
    {
        $now = new \DateTimeImmutable();

        return (int) $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.customer = :user')
            ->andWhere('a.startAt > :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findUserRecentAppointments($user, int $limit = 5): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.customer = :user')
            ->setParameter('user', $user)
            ->orderBy('a.startAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

public function getMonthlyRevenueData(): array
{
    $currentYear = date('Y');
    $revenueData = [];
    
    // Get last 6 months (including current month)
    for ($i = 5; $i >= 0; $i--) {
        $date = new \DateTimeImmutable("first day of -{$i} months");
        $startDate = new \DateTimeImmutable($date->format('Y-m-01 00:00:00'));
        $endDate = $startDate->modify('last day of this month 23:59:59');
        
        $result = $this->createQueryBuilder('a')
            ->select('SUM(a.amount) as revenue')
            ->where('a.status = :status')
            ->andWhere('a.startAt BETWEEN :start AND :end')
            ->setParameter('status', 'Completed')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
        
        $revenueData[$startDate->format('M Y')] = $result ? (float) $result : 0;
    }
    
    return $revenueData;
}

public function getTotalRevenue(): float
{
    $result = $this->createQueryBuilder('a')
        ->select('SUM(a.amount) as total')
        ->where('a.status = :status')
        ->setParameter('status', 'Completed')
        ->getQuery()
        ->getSingleScalarResult();
    
    return $result ? (float) $result : 0;
}

public function getCurrentMonthRevenue(): float
{
    $start = new \DateTimeImmutable('first day of this month 00:00:00');
    $end = $start->modify('last day of this month 23:59:59');
    
    $result = $this->createQueryBuilder('a')
        ->select('SUM(a.amount) as revenue')
        ->where('a.status = :status')
        ->andWhere('a.startAt BETWEEN :start AND :end')
        ->setParameter('status', 'Completed')
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getQuery()
        ->getSingleScalarResult();
    
    return $result ? (float) $result : 0;
}

public function getLastMonthRevenue(): float
{
    $start = new \DateTimeImmutable('first day of last month 00:00:00');
    $end = $start->modify('last day of this month 23:59:59');
    
    $result = $this->createQueryBuilder('a')
        ->select('SUM(a.amount) as revenue')
        ->where('a.status = :status')
        ->andWhere('a.startAt BETWEEN :start AND :end')
        ->setParameter('status', 'Completed')
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->getQuery()
        ->getSingleScalarResult();
    
    return $result ? (float) $result : 0;
}

}

