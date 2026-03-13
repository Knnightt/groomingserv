<?php
namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
    

    public function load(ObjectManager $manager): void
    {
        $subscription1 = new Subscription();
        $subscription1->setUser($this->getReference(UserFixtures::CUSTOMER1_REFERENCE, User::class));
        $subscription1->setPlan('Premium');
        $subscription1->setStatus('active');
        $subscription1->setAmount('29.99');
        $subscription1->setStartDate(new \DateTimeImmutable('-1 month'));
        $subscription1->setRenewalDate(new \DateTimeImmutable('+1 month'));
        
        $manager->persist($subscription1);

        $subscription2 = new Subscription();
        $subscription2->setUser($this->getReference(UserFixtures::CUSTOMER2_REFERENCE, User::class));
        $subscription2->setPlan('Basic');
        $subscription2->setStatus('active');
        $subscription2->setAmount('19.99');
        $subscription2->setStartDate(new \DateTimeImmutable('-15 days'));
        $subscription2->setRenewalDate(new \DateTimeImmutable('+15 days'));
        
        $manager->persist($subscription2);

        $manager->flush();
    }
}