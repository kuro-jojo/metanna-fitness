<?php

namespace App\DataFixtures;

use App\Entity\Settings;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User;

        $user->setNom("Doe");
        $user->setPrenom("John");
        $user->setEmail("admin@example.com");
        $user->setTelephone("77 830 96 15");
        $user->setRoles(["ROLE_ADMIN", "ROLE_RESPONSABLE"]);
        $user->setPassword($this->encoder->encodePassword($user,"admin"));

        $manager->persist($user);

        $settings = new Settings;

        $settings->setDefaultRegistrationAmount(25000);
        $settings->setDefaultSubsAmount(15000);
        $settings->setCode("ADMIN");

        $manager->persist($settings);
        $manager->flush();
    }
}
