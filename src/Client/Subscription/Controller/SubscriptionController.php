<?php

namespace App\Client\Subscription\Controller;

use DateInterval;
use App\Client\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{

    private $flashy;

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }

    /**
     * @Route("/client/subscription/renew/{id<\d+>}", name="app_client_subscription_renew")
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
        $dateSubsStart = clone $subscription->getStartOfSubs();
        $dateSubsEnd = clone $subscription->getEndOfSubs();

        date_add($dateSubsStart, new DateInterval("P1M"));
        date_add($dateSubsEnd, new DateInterval("P1M"));

        $subscription->setStartOfSubs($dateSubsStart);
        $subscription->setEndOfSubs($dateSubsEnd);
        $em->flush();
        $this->flashy->info("Abonnement renouvelé !!");

        return $this->redirectToRoute("app_client_registration_list");
    }
}
