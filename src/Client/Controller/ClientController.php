<?php

namespace App\Client\Controller;

use App\Client\Entity\Client;
use App\Client\Entity\ClientSearch;
use App\Client\Form\ClientSearchType;
use App\Client\Repository\ClientRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @IsGranted("ROLE_RESPONSABLE")
 * @Route("/client", name="app_client")
 */

class ClientController extends AbstractController
{

    /**
     * @Route("/registration/search",name="_registration_search", methods={"GET"})
     * searchClient
     *
     * @param  mixed $request
     * @param  mixed $clientRepository
     * @return Response
     */
    public function searchClient(Request $request, ClientRepository $clientRepository): Response
    {
        $error = null;
        $form = null;
        $clientCode = null;
        $this->search($request, $clientRepository, $form, $error, $clientCode);
        $client = $clientCode != null ? $clientRepository->find($clientCode) : null;
        if ($clientCode == null) {
            return $this->redirectToRoute("app_client_registration_list");
        }
        return $this->render('client/registration/list.html.twig', [
            'client' => $client,
            'error' => $error,
            'clientCode' => $clientCode,
            'checked'=> ' '
        ]);
    }

    /**
     * @Route("/registration/cancel", name="_registration_cancel_search", methods={"GET"})
     * 
     * search a customer
     *
     * @return Response
     */
    public function searchForCancel(Request $request, ClientRepository $clientRepository): Response
    {
        $error = null;
        $form = null;
        $clientCode = null;
        $this->search($request, $clientRepository, $form, $error, $clientCode);
        $client = $clientCode != null ? $clientRepository->find($clientCode) : null;

        return $this->render('client/registration/cancel.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
            'error' => $error,
            'clientCode' => $clientCode
        ]);
    }

    /**
     * @Route("/subscription/renew", name="_subscription_renew_search", methods={"GET"})
     * 
     * search a customer
     *
     * @return Response
     */
    public function searchForSubs(Request $request, ClientRepository $clientRepository): Response
    {
        $error = null;
        $form = null;
        $clientCode = null;
        $timeRemaining = null;
        $this->search($request, $clientRepository, $form, $error, $clientCode);
        $client = $clientCode != null ? $clientRepository->find($clientCode) : null;

        if ($clientCode != null  && $client != null && $client->getMyRegistration() == null) {
            $client = null;
        }
        if ($client != null) {
            $subscriptionEnd = $client->getMySubscription()->getEndOfSubs();
            $subscriptionStart = $client->getMySubscription()->getStartOfSubs();

            // $nowDay = date("j");
            if ($subscriptionStart <= new \DateTime()) {
                // Check subscription state 
                // if ($nowDay < $subscriptionEndDay) {
                //     $subscriptionEndDay += 30;
                // }
                // Get the remaining days before subs expiration
                $timeRemaining =  $subscriptionEnd->diff(new \DateTime(), true)->days;
            }
        }
        return $this->render('client/subscription/renew.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
            'error' => $error,
            'clientCode' => $clientCode,
            'timeRemaining' => $timeRemaining,

        ]);
    }

    private function search(Request $request, ClientRepository $clientRepository, &$form, &$error, &$clientCode)
    {
        $clientSearch = new ClientSearch;
        $form = $this->createForm(ClientSearchType::class, $clientSearch);
        $clientCode = $request->query->get("clientCode");
        if ($clientCode != null) {

            if (preg_match("/^\d+$/", $request->query->get("clientCode"))) {
                $form->handleRequest($request);
            } else {
                $error = "Code client incorrect";
                $clientCode = null;
            }
        }
    }
}
