<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResponsableServiceController extends AbstractController
{

   #[Route('/responsable/service', name : 'app_responsable_service')]
   /**
    * 
    * @IsGranted("ROLE_ADMIN")
    */

   public function allServices(ServiceRepository $serviceRepository, SessionInterface $session): Response
   {
      $services = $serviceRepository->findAll();
      return $this->render('responsable/services.html.twig', [
         'services' => $services
      ]);
   }
}
