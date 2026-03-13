<?php
namespace App\DataFixtures;

use App\Entity\Pet;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PetFixtures extends Fixture implements DependentFixtureInterface
{
    public const BUDDY_REFERENCE = 'buddy-pet';
    public const LUCY_REFERENCE = 'lucy-pet';
    public const MAX_REFERENCE = 'max-pet';
    public const BELLA_REFERENCE = 'bella-pet';

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        // Customer 1's Pets
        $buddy = new Pet();
        $buddy->setName('Buddy');
        $buddy->setSpecies('Dog');
        $buddy->setBreed('Golden Retriever');
        $buddy->setAge(3);
        $buddy->setGender('Male');
        $buddy->setWeight(65.5);
        $buddy->setLifeStage('Adult');
        $buddy->setTemperament('Friendly');
        $buddy->setIsNeutered(true);
        $buddy->setIsVaccinated(true);
        $buddy->setOwner($this->getReference(UserFixtures::CUSTOMER1_REFERENCE, User::class));
        
        $manager->persist($buddy);
        $this->addReference(self::BUDDY_REFERENCE, $buddy);

        $lucy = new Pet();
        $lucy->setName('Lucy');
        $lucy->setSpecies('Cat');
        $lucy->setBreed('Domestic Shorthair');
        $lucy->setAge(2);
        $lucy->setGender('Female');
        $lucy->setWeight(8.2);
        $lucy->setLifeStage('Adult');
        $lucy->setTemperament('Calm');
        $lucy->setIsNeutered(true);
        $lucy->setIsVaccinated(true);
        $lucy->setMedicalNotes('Mild skin sensitivity');
        $lucy->setOwner($this->getReference(UserFixtures::CUSTOMER1_REFERENCE, User::class));
        
        $manager->persist($lucy);
        $this->addReference(self::LUCY_REFERENCE, $lucy);

        // Customer 2's Pets
        $max = new Pet();
        $max->setName('Max');
        $max->setSpecies('Dog');
        $max->setBreed('German Shepherd');
        $max->setAge(5);
        $max->setGender('Male');
        $max->setWeight(75.0);
        $max->setLifeStage('Adult');
        $max->setTemperament('Energetic');
        $max->setIsNeutered(true);
        $max->setIsVaccinated(true);
        $max->setCoatType('Double Coat');
        $max->setAllergies('None known');
        $max->setOwner($this->getReference(UserFixtures::CUSTOMER2_REFERENCE, User::class));
        
        $manager->persist($max);
        $this->addReference(self::MAX_REFERENCE, $max);

        $bella = new Pet();
        $bella->setName('Bella');
        $bella->setSpecies('Dog');
        $bella->setBreed('Poodle');
        $bella->setAge(1);
        $bella->setGender('Female');
        $bella->setWeight(12.5);
        $bella->setLifeStage('Adult');
        $bella->setTemperament('Playful');
        $bella->setIsNeutered(false);
        $bella->setIsVaccinated(true);
        $bella->setCoatType('Curly');
        $bella->setOwner($this->getReference(UserFixtures::CUSTOMER2_REFERENCE, User::class));
        
        $manager->persist($bella);
        $this->addReference(self::BELLA_REFERENCE, $bella);

        $manager->flush();
    }
}