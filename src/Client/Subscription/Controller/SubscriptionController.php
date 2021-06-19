<?php

namespace App\Client\Subscription\Controller;

use App\Client\Entity\Client;
use Flasher\Prime\FlasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Client\Repository\ClientRepository;
use App\Service\ResponsableActivityTracker;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{

    private const SUBSCRIPTION_ACTIVITY = "Abonnement du client n°";
    private const SUBSCRIPTION_LIST_ACTIVITY = "Visualisation des abonnés";

    private $flasher;
    private $responsableTracker;

    public function __construct(FlasherInterface $flasher,ResponsableActivityTracker $responsableTracker)
    {
        $this->flasher = $flasher;
        $this->responsableTracker = $responsableTracker;
    }


    #[Route('/client/subscription/renew/{id<\d+>}', name: 'app_client_subscription_renew')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SUBSCRIBE_CLIENT') or is_granted('ROLE_ADMIN')")
     * 
     * renew a customer subscription
     *
     * @param  mixed $client
     * @param  mixed $em
     * @return Response
     */
    public function renew(Client $client, EntityManagerInterface $em): Response
    {
        $subscription = $client->getMySubscription();
        $dateSubsStart = $subscription->getStartOfSubs();
        $dateSubsEnd = clone $subscription->getEndOfSubs();

        // Si l'abonnement a expirée on redéfinit la date de début de l'abonnement
        if ($dateSubsEnd <= new \DateTime) {
            $dateSubsStart = new \DateTime;
            $subscription->setStartOfSubs($dateSubsStart);
        }
        date_add($dateSubsEnd, new \DateInterval("P1M"));
        $subscription->setEndOfSubs($dateSubsEnd);

        // $user = $this->getUser();
        // $user->addSubsRealized($subscription);

        $em->flush();
        $this->flasher->addInfo("Abonnement renouvelé !!");
        $this->responsableTracker->saveTracking($this::SUBSCRIPTION_ACTIVITY . $client->getId(),$this->getUser());

        return $this->redirectToRoute("app_client_subscription_list");
    }

    #[Route('/client/subscription/list', name: 'app_client_subscription_list')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_LIST_SUBSCRIPTION') or is_granted('ROLE_ADMIN')")
     * 
     * list of all subscripted customers
     *
     * @param  mixed $clientRepository
     * @return Response
     */
    public function listOfSubscription(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findOnlyRegistered();
        $timeRemaining = null;

        foreach ($clients as $key => $client) {
            if ($client->getMySubscription() == null) {
                unset($clients[$key]);
            } else {
                $timeRemaining[$client->getId()] = null;

                $subscriptionEnd = $client->getMySubscription()->getEndOfSubs();
                $subscriptionStart = $client->getMySubscription()->getStartOfSubs();
                if ($subscriptionStart <= new \DateTime()) {
                    $today = new \DateTime;

                    $time =  date_diff($today,$subscriptionEnd)->format("%r%a");
                    $timeRemaining[$client->getId()] = $time;
                }
            }
        }

        $this->responsableTracker->saveTracking($this::SUBSCRIPTION_LIST_ACTIVITY,$this->getUser());

        return $this->render("client/subscription/list.html.twig", [
            'clients' => $clients,
            'timeRemaining' => $timeRemaining
        ]);
    }
}
