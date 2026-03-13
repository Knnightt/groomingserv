<?php
namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\User;
use App\Entity\Pet;
use App\Entity\Service;
use App\Entity\Staff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PetFixtures::class,
            ServiceFixtures::class,
            StaffFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        // Upcoming Appointment
        $appointment1 = new Appointment();
        $appointment1->setCustomer($this->getReference(UserFixtures::CUSTOMER1_REFERENCE, User::class));
        $appointment1->setPet($this->getReference(PetFixtures::BUDDY_REFERENCE, Pet::class));
        $appointment1->setService($this->getReference(ServiceFixtures::FULL_GROOMING_REFERENCE, Service::class));
        $appointment1->setAssignedStaff($this->getReference(StaffFixtures::STAFF1_REFERENCE, Staff::class));
        
        $start1 = new \DateTimeImmutable('tomorrow 10:00');
        $appointment1->setStartAt($start1);
        $appointment1->setEndAt($start1->modify('+90 minutes'));
        $appointment1->setAmount(65.00);
        $appointment1->setStatus(Appointment::STATUS_CONFIRMED);
        $appointment1->setNotes('Buddy gets anxious during blow drying');
        
        $manager->persist($appointment1);

        // Completed Appointment
        $appointment2 = new Appointment();
        $appointment2->setCustomer($this->getReference(UserFixtures::CUSTOMER2_REFERENCE, User::class));
        $appointment2->setPet($this->getReference(PetFixtures::MAX_REFERENCE, Pet::class));
        $appointment2->setService($this->getReference(ServiceFixtures::BASIC_GROOMING_REFERENCE, Service::class));
        $appointment2->setAssignedStaff($this->getReference(StaffFixtures::STAFF2_REFERENCE, Staff::class));
        
        $start2 = new \DateTimeImmutable('-2 days 14:00');
        $appointment2->setStartAt($start2);
        $appointment2->setEndAt($start2->modify('+60 minutes'));
        $appointment2->setAmount(45.00);
        $appointment2->setStatus(Appointment::STATUS_COMPLETED);
        $appointment2->setGroomerNotes('Good behavior during bath');
        $appointment2->setIsPaid(true);
        $appointment2->setPaymentMethod('Credit Card');
        
        $manager->persist($appointment2);

        // Pending Appointment
        $appointment3 = new Appointment();
        $appointment3->setCustomer($this->getReference(UserFixtures::CUSTOMER1_REFERENCE, User::class));
        $appointment3->setPet($this->getReference(PetFixtures::LUCY_REFERENCE, Pet::class));
        $appointment3->setService($this->getReference(ServiceFixtures::NAIL_TRIM_REFERENCE, Service::class));
        
        $start3 = new \DateTimeImmutable('+3 days 11:30');
        $appointment3->setStartAt($start3);
        $appointment3->setEndAt($start3->modify('+15 minutes'));
        $appointment3->setAmount(15.00);
        $appointment3->setStatus(Appointment::STATUS_PENDING);
        $appointment3->setNotes('Lucy needs gentle handling for nails');
        
        $manager->persist($appointment3);

        // Deluxe Package Appointment
        $appointment4 = new Appointment();
        $appointment4->setCustomer($this->getReference(UserFixtures::CUSTOMER2_REFERENCE, User::class));
        $appointment4->setPet($this->getReference(PetFixtures::BELLA_REFERENCE, Pet::class));
        $appointment4->setService($this->getReference(ServiceFixtures::DELUXE_REFERENCE, Service::class));
        $appointment4->setAssignedStaff($this->getReference(StaffFixtures::STAFF1_REFERENCE, Staff::class));
        
        $start4 = new \DateTimeImmutable('+1 week 09:00');
        $appointment4->setStartAt($start4);
        $appointment4->setEndAt($start4->modify('+2 hours'));
        $appointment4->setAmount(85.00);
        $appointment4->setStatus(Appointment::STATUS_CONFIRMED);
        $appointment4->setNotes('First deluxe package for Bella');
        
        $manager->persist($appointment4);

        $manager->flush();
    }
}