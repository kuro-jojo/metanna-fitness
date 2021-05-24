<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @IsGranted("ROLE_RESPONSABLE")
     * @Route("/home", name="app_home")
     */
    public function index(Request $request,SessionInterface $session): Response
    {
        return $this->render('home/index.html.twig');
    }
}