<?php

namespace App\Controller;

use App\Entity\CasualClient;
use App\Form\CasualClientType;
use Flasher\Prime\FlasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CasualClientRepository;
use App\Service\ResponsableActivityTracker;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CasualClientController extends AbstractController
{   

    private const CASUAL_CLIENT_SAVE_ACTIVITY = "Enregistrement d'un client occasionnel";

    #[Route('/client/casual', name: 'app_client_casual')]
    /**
     * save
     *
     * @param  mixed $request
     * @param  mixed $em
     * @return Response
     */
    public function save(Request $request, EntityManagerInterface $em, FlasherInterface $flasher, ResponsableActivityTracker $responsableTracker): Response
    {

        $causalClient = new CasualClient;
        $form = $this->createForm(CasualClientType::class, $causalClient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $causalClient->setDoneOn(new \DateTime());
            $em->persist($causalClient);
            $em->flush();

            $flasher->addSuccess('Client enregistré.');

            $responsableTracker->saveTracking($this::CASUAL_CLIENT_SAVE_ACTIVITY, $this->getUser());

            return $this->redirectToRoute('app_client_casual_list');
        }
        return $this->render('client/casual_client/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/casual/list', name: 'app_client_casual_list')]

    /**
     * list
     *
     * @param  mixed $casualClientRepository
     * @return Response
     */
    public function list(Request $request, CasualClientRepository $casualClientRepository, PaginatorInterface $paginator): Response
    {

        $casualClients = $paginator->paginate($casualClientRepository->findAllQuery(), $request->query->getInt('page', 1), 15);

        return $this->render('client/casual_client/list.html.twig', [
            'casualClients' => $casualClients
        ]);
    }
}
