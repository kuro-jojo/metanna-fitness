<?php

namespace App\Controller;

use App\Entity\CasualClient;
use App\Form\CasualClientType;
use Flasher\Toastr\Prime\ToastrFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CasualClientRepository;
use App\Service\ResponsableActivityTracker;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;

class CasualClientController extends AbstractController
{

    private const CASUAL_CLIENT_SAVE_ACTIVITY = "Enregistrement d'un client occasionnel";

    #[Route('/client/casual', name: 'app_client_casual')]
    #[IsGranted(new Expression("is_granted('ROLE_RIGHT_CASUAL') or is_granted('ROLE_ADMIN')" ))]
    /**
     * 
     * save
     *
     * @param  mixed $request
     * @param  mixed $em
     * @return Response
     */
    public function save(Request $request, EntityManagerInterface $em, ToastrFactory $flasher, ResponsableActivityTracker $responsableTracker): Response
    {

        $casualClient = new CasualClient;
        $form = $this->createForm(CasualClientType::class, $casualClient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $casualClient->setDoneOn(new \DateTime());
            $casualClient->setResponsableOfRecord($this->getUser());
            $em->persist($casualClient);
            $em->flush();

            $flasher->success('Client enregistré.');

            $responsableTracker->saveTracking($this::CASUAL_CLIENT_SAVE_ACTIVITY, $this->getUser());

            return $this->redirectToRoute('app_client_casual_list');
        }
        return $this->render('client/casual_client/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/casual/list', name: 'app_client_casual_list')]

    #[IsGranted(new Expression("is_granted('ROLE_RIGHT_LIST_CASUAL') or is_granted('ROLE_ADMIN')" ))]
    /**
     * 
     * list
     *
     * @param  mixed $casualClientRepository
     * @return Response
     */
    public function list(Request $request, CasualClientRepository $casualClientRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted('ROLE_ADMIN'))
            $casualClients = $paginator->paginate($casualClientRepository->findAllQuery(), $request->query->getInt('page', 1), 15);
        else
            $casualClients = $paginator->paginate($casualClientRepository->findAllByResponsableQuery($this->getUser()->getId()), $request->query->getInt('page', 1), 15);

        return $this->render('client/casual_client/list.html.twig', [
            'casualClients' => $casualClients
        ]);
    }
}
