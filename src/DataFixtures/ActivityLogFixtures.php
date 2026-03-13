<?php
namespace App\DataFixtures;

use App\Entity\ActivityLog;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivityLogFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $log1 = new ActivityLog();
        $log1->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class));
        $log1->setAction('SYSTEM_INITIALIZED');
        $log1->setDescription('Application fixtures loaded');
        $log1->setIpAddress('127.0.0.1');
        $log1->setRoute('app_fixtures_load');
        
        $manager->persist($log1);

        $log2 = new ActivityLog();
        $log2->setUser($this->getReference(UserFixtures::CUSTOMER1_REFERENCE, User::class));
        $log2->setAction('PET_REGISTERED');
        $log2->setDescription('Registered new pet: Buddy');
        $log2->setIpAddress('192.168.1.100');
        $log2->setRoute('app_pet_new');
        
        $manager->persist($log2);

        $log3 = new ActivityLog();
        $log3->setUser($this->getReference(UserFixtures::CUSTOMER2_REFERENCE, User::class));
        $log3->setAction('APPOINTMENT_BOOKED');
        $log3->setDescription('Booked appointment for Max');
        $log3->setIpAddress('192.168.1.101');
        $log3->setRoute('app_appointment_new');
        
        $manager->persist($log3);

        $log4 = new ActivityLog();
        $log4->setUser($this->getReference(UserFixtures::STAFF1_REFERENCE, User::class));
        $log4->setAction('APPOINTMENT_COMPLETED');
        $log4->setDescription('Completed grooming for Buddy');
        $log4->setIpAddress('192.168.1.50');
        $log4->setRoute('app_appointment_complete');
        
        $manager->persist($log4);

        $manager->flush();
    }
}