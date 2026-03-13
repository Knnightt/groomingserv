<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const CUSTOMER1_REFERENCE = 'customer1-user';
    public const CUSTOMER2_REFERENCE = 'customer2-user';
    public const STAFF1_REFERENCE = 'staff1-user';
    public const STAFF2_REFERENCE = 'staff2-user';
    public const MANAGER_USER_REFERENCE = 'manager-user';

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Admin User
        $admin = new User();
        $admin->setEmail('admin@quibot.com');
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setIsVerified(true);
        
        // Admin Profile
        $adminProfile = new UserProfile();
        $adminProfile->setFullName('System Administrator');
        $adminProfile->setPhoneNumber('555-0101');
        $adminProfile->setAddress('123 Admin Street');
        $adminProfile->setCity('Springfield');
        $adminProfile->setState('IL');
        $adminProfile->setZipCode('62701');
        $adminProfile->setCountry('USA');
        $admin->setUserProfile($adminProfile);
        
        $manager->persist($admin);
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);

        // 2. Manager User
        $managerUser = new User();
        $managerUser->setEmail('manager@quibot.com');
        $managerUser->setUsername('manager');
        $managerUser->setPassword($this->passwordHasher->hashPassword($managerUser, 'manager123'));
        $managerUser->setRoles(['ROLE_MANAGER', 'ROLE_USER']);
        $managerUser->setIsVerified(true);
        
        $managerProfile = new UserProfile();
        $managerProfile->setFullName('testing');
        $managerProfile->setPhoneNumber('555-0102');
        $managerProfile->setAddress('456 Manager Ave');
        $managerProfile->setCity('Springfield');
        $managerProfile->setZipCode('62701');
        $managerUser->setUserProfile($managerProfile);
        
        $manager->persist($managerUser);
        $this->addReference(self::MANAGER_USER_REFERENCE, $managerUser);

        // 3. Staff Users
        $staff1 = new User();
        $staff1->setEmail('staff@quibot.com');
        $staff1->setUsername('groomer1');
        $staff1->setPassword($this->passwordHasher->hashPassword($staff1, 'staff123'));
        $staff1->setRoles(['ROLE_STAFF', 'ROLE_USER']);
        $staff1->setIsVerified(true);
        
        $staff1Profile = new UserProfile();
        $staff1Profile->setFullName('mike');
        $staff1Profile->setPhoneNumber('555-0103');
        $staff1Profile->setGender('Male');
        $staff1->setUserProfile($staff1Profile);
        
        $manager->persist($staff1);
        $this->addReference(self::STAFF1_REFERENCE, $staff1);

        $staff2 = new User();
        $staff2->setEmail('groomer2@petsalon.com');
        $staff2->setUsername('groomer2');
        $staff2->setPassword($this->passwordHasher->hashPassword($staff2, 'staff123'));
        $staff2->setRoles(['ROLE_STAFF', 'ROLE_USER']);
        $staff2->setIsVerified(true);
        
        $staff2Profile = new UserProfile();
        $staff2Profile->setFullName('Elma');
        $staff2Profile->setPhoneNumber('555-0104');
        $staff2Profile->setGender('Female');
        $staff2->setUserProfile($staff2Profile);
        
        $manager->persist($staff2);
        $this->addReference(self::STAFF2_REFERENCE, $staff2);

        // 4. Customer Users
        $customer1 = new User();
        $customer1->setEmail('test@gmail.com');
        $customer1->setUsername('test');
        $customer1->setPassword($this->passwordHasher->hashPassword($customer1, 'customer123'));
        $customer1->setRoles(['ROLE_USER']);
        $customer1->setIsVerified(true);
        
        $customer1Profile = new UserProfile();
        $customer1Profile->setFullName('test test');
        $customer1Profile->setPhoneNumber('555-0201');
        $customer1Profile->setAddress('789 Oak Street');
        $customer1Profile->setCity('Springfield');
        $customer1Profile->setZipCode('62702');
        $customer1Profile->setGender('Male');
        $customer1->setUserProfile($customer1Profile);
        
        $manager->persist($customer1);
        $this->addReference(self::CUSTOMER1_REFERENCE, $customer1);

        $customer2 = new User();
        $customer2->setEmail('customer2@gmail.com');
        $customer2->setUsername('cleint');
        $customer2->setPassword($this->passwordHasher->hashPassword($customer2, 'customer123'));
        $customer2->setRoles(['ROLE_USER']);
        $customer2->setIsVerified(true);
        
        $customer2Profile = new UserProfile();
        $customer2Profile->setFullName('ekang');
        $customer2Profile->setPhoneNumber('555-0202');
        $customer2Profile->setAddress('321 Pine Road');
        $customer2Profile->setCity('Springfield');
        $customer2Profile->setZipCode('62703');
        $customer2Profile->setGender('Female');
        $customer2->setUserProfile($customer2Profile);
        
        $manager->persist($customer2);
        $this->addReference(self::CUSTOMER2_REFERENCE, $customer2);

        $manager->flush();
    }
}