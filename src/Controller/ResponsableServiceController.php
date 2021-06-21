<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResponsableServiceController extends AbstractController
{

   #[Route('/responsable/service', name: 'app_responsable_service')]
   /**
    * 
     @Security("is_granted('ROLE_RIGHT_RESPONSABLE_ACTIVITIES') or is_granted('ROLE_ADMIN')")
    */

   public function allServices(ServiceRepository $serviceRepository): Response
   {
      $services = $serviceRepository->findAll();
      return $this->render('responsable/services.html.twig', [
         'services' => $services
      ]);
   }
}
