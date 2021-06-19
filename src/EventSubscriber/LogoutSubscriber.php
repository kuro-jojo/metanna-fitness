<?php

namespace App\EventSubscriber;

use App\Entity\Service;
use App\Repository\UserRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ResponsableActivityTracker;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutSubscriber implements EventSubscriberInterface
{

    private const SERVICE_NAME = 'Déconnexion';

    private $em;
    private $rep;
    private $responsableTracker;

    public function __construct(EntityManagerInterface $em ,UserRepository $rep,ResponsableActivityTracker $responsableTracker)
    {
            $this->em = $em;
            $this->rep = $rep;
            $this->responsableTracker = $responsableTracker;
    }

    public function onLogoutEvent(LogoutEvent $event)
    {    
        if ($event->getRequest()->getSession()->get('userId')) {
            
            $id = $event->getRequest()->getSession()->get('userId');
            $user = $this->rep->find($id);
            $this->responsableTracker->saveTracking($this::SERVICE_NAME,$user);
            
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
            SessionEvent::class => 'onSession'
        ];
    }
}
