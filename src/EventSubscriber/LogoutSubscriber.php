<?php

namespace App\EventSubscriber;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutSubscriber implements EventSubscriberInterface
{
    private $em;
    private $rep;
    public function __construct(EntityManagerInterface $em ,ServiceRepository $rep)
    {
            $this->em = $em;
            $this->rep = $rep;
    }

    public function onLogoutEvent(LogoutEvent $event)
    {    
        if ($event->getRequest()->getSession()->get('service')) {
            
            $id = $event->getRequest()->getSession()->get('service')->getId();
            $service =$this->rep->find($id);
            $service->setEndOfService(new \DateTime());
            
            $this->em->flush();
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
