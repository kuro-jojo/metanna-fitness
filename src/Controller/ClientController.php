<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientRegisterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    /**
     * @IsGranted("ROLE_RESPONSABLE")
     * @Route("/client/register", name="app_register_client")
     */
    public function register(Request $request): Response
    {
        $client = new Client;
        $form = $this->createForm(ClientRegisterFormType::class,$client);
        
        return $this->render('client/registration.html.twig', [
            'formClientRegister'=>$form->createView(),
        ]);
    }
}
