<?php

namespace App\DataFixtures;

use App\Entity\Settings;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) : void
    {
        $user = new User;

        $user->setNom("Doe");
        $user->setPrenom("Jonathan");
        $user->setEmail("admin@example.com");
        $user->setTelephone("77 777 77 77");
        $user->setRoles(["ROLE_ADMIN", "ROLE_RESPONSABLE"]);
        $user->setPassword($this->encoder->hashPassword($user,"admin"));
        $user->setIsVerified(true);
        $user->setProfileFileName("profile_icon.png");
        $manager->persist($user);

        $settings = new Settings;

        $settings->setDefaultRegistrationAmount(25000);
        $settings->setDefaultSubsAmount(15000);
        $settings->setCode("ADMIN");

        $manager->persist($settings);
        $manager->flush();
    }
}
