<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function index(Request $request): Response
    {   
        $color = $request->get("themePrincipal");
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
            'color' => $color
        ]);
    }
}
