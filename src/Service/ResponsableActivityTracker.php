<?php

namespace App\Service;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ResponsableActivityTracker{

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function saveTracking($activity, UserInterface $user){

        $service = new Service;
        $service->setResponsable($user);
        $service->setDateOfService(new \DateTime);
        $service->setServiceName($activity);
        $this->em->flush();

        $this->em->persist($service);
        $this->em->flush();
    }
}