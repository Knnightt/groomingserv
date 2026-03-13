<?php
namespace App\DataFixtures;

use App\Entity\Staff;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StaffFixtures extends Fixture implements DependentFixtureInterface
{
    public const STAFF1_REFERENCE = 'staff1';
    public const STAFF2_REFERENCE = 'staff2';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        // Staff 1 - Groomer
        $staff1 = new Staff();
        $staff1->setUser($this->getReference(UserFixtures::STAFF1_REFERENCE, User::class));
        $staff1->setStaffId('STAFF001');
        $staff1->setStaffRole(Staff::ROLE_GROOMER);
        $staff1->setSpecializations(['Dog Grooming', 'Cat Grooming', 'Nail Trimming']);
        $staff1->setExperienceYears(5);
        $staff1->setHourlyRate(25.00);
        $staff1->setHireDate(new \DateTimeImmutable('2020-01-15'));
        $staff1->setCanHandleAggressivePets(true);
        $staff1->setIsCertified(true);
        $staff1->setCertifications('Certified Professional Groomer');
        $staff1->setWorkingDays(['Monday', 'Wednesday', 'Friday', 'Saturday']);
        $staff1->setStartTime(new \DateTime('09:00:00'));
        $staff1->setEndTime(new \DateTime('17:00:00'));
        
        $manager->persist($staff1);
        $this->addReference(self::STAFF1_REFERENCE, $staff1);

        // Staff 2 - Groomer/Manager
        $staff2 = new Staff();
        $staff2->setUser($this->getReference(UserFixtures::STAFF2_REFERENCE, User::class));
        $staff2->setStaffId('STAFF002');
        $staff2->setStaffRole(Staff::ROLE_GROOMER);
        $staff2->setSpecializations(['Show Grooming', 'Specialty Cuts', 'Skin Treatments']);
        $staff2->setExperienceYears(8);
        $staff2->setHourlyRate(30.00);
        $staff2->setHireDate(new \DateTimeImmutable('2018-03-20'));
        $staff2->setCanHandleAggressivePets(false);
        $staff2->setIsCertified(true);
        $staff2->setCertifications('Master Groomer, Canine Cosmetology');
        $staff2->setWorkingDays(['Tuesday', 'Thursday', 'Saturday']);
        $staff2->setStartTime(new \DateTime('10:00:00'));
        $staff2->setEndTime(new \DateTime('18:00:00'));
        
        $manager->persist($staff2);
        $this->addReference(self::STAFF2_REFERENCE, $staff2);

        $manager->flush();
    }
}