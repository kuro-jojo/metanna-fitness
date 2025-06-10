<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    #[IsGranted(new Expression("is_granted('ROLE_RESPONSABLE') or is_granted('ROLE_ADMIN')" ))]
    /** 
     * 
     * index
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
