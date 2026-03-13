<?php
namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    public const BASIC_GROOMING_REFERENCE = 'basic-grooming';
    public const FULL_GROOMING_REFERENCE = 'full-grooming';
    public const NAIL_TRIM_REFERENCE = 'nail-trim';
    public const BATH_ONLY_REFERENCE = 'bath-only';
    public const DELUXE_REFERENCE = 'deluxe-package';

    public function load(ObjectManager $manager): void
    {
        // Basic Grooming
        $basicGrooming = new Service();
        $basicGrooming->setName('Basic Grooming');
        $basicGrooming->setDescription('Includes bath, blow dry, brushing, nail trim, and ear cleaning');
        $basicGrooming->setPrice(45.00);
        $basicGrooming->setDuration(60);
        $basicGrooming->setCategory('Basic');
        $basicGrooming->setFeatures('Bath, Dry, Brush, Nail Trim, Ear Cleaning');
        
        $manager->persist($basicGrooming);
        $this->addReference(self::BASIC_GROOMING_REFERENCE, $basicGrooming);

        // Full Grooming
        $fullGrooming = new Service();
        $fullGrooming->setName('Full Grooming');
        $fullGrooming->setDescription('Complete grooming package with haircut/style');
        $fullGrooming->setPrice(65.00);
        $fullGrooming->setDuration(90);
        $fullGrooming->setCategory('Premium');
        $fullGrooming->setFeatures('Everything in Basic plus Haircut/Style');
        
        $manager->persist($fullGrooming);
        $this->addReference(self::FULL_GROOMING_REFERENCE, $fullGrooming);

        // Nail Trim Only
        $nailTrim = new Service();
        $nailTrim->setName('Nail Trim');
        $nailTrim->setDescription('Professional nail trimming and filing');
        $nailTrim->setPrice(15.00);
        $nailTrim->setDuration(15);
        $nailTrim->setCategory('Basic');
        
        $manager->persist($nailTrim);
        $this->addReference(self::NAIL_TRIM_REFERENCE, $nailTrim);

        // Bath Only
        $bathOnly = new Service();
        $bathOnly->setName('Bath & Brush');
        $bathOnly->setDescription('Professional bathing and brushing without haircut');
        $bathOnly->setPrice(30.00);
        $bathOnly->setDuration(45);
        $bathOnly->setCategory('Basic');
        
        $manager->persist($bathOnly);
        $this->addReference(self::BATH_ONLY_REFERENCE, $bathOnly);

        // Deluxe Package
        $deluxe = new Service();
        $deluxe->setName('Deluxe Spa Package');
        $deluxe->setDescription('Premium package with blueberry facial, teeth brushing, and paw balm');
        $deluxe->setPrice(85.00);
        $deluxe->setDuration(120);
        $deluxe->setCategory('Ultimate');
        $deluxe->setFeatures('Full Grooming plus Blueberry Facial, Teeth Brushing, Paw Balm');
        
        $manager->persist($deluxe);
        $this->addReference(self::DELUXE_REFERENCE, $deluxe);

        $manager->flush();
    }
}