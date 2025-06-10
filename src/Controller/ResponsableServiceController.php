<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ServiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;

class ResponsableServiceController extends AbstractController
{

   #[Route('/responsable/service', name: 'app_responsable_service')]
   #[IsGranted(new Expression("is_granted('ROLE_RIGHT_RESPONSABLE_ACTIVITIES') or is_granted('ROLE_ADMIN')" ))]
   /**
    * 
    * all services
    *
    * @return Response
    */

   public function allServices(Request $request, ServiceRepository $serviceRepository, PaginatorInterface $paginator): Response
   {

      $services = $paginator->paginate($serviceRepository->findAllQuery(), $request->query->getInt('page', 1), 20);

      return $this->render('responsable/services.html.twig', [
         'services' => $services
      ]);
   }
}
