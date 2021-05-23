<?php

namespace App\Client\Registration\Controller;

use DateInterval;
use App\Client\Entity\Client;
use App\Service\FileUploader;
use App\Client\Entity\ClientSearch;
use App\Client\Form\ClientSearchType;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Client\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Client\Registration\Entity\Registration;
use App\Client\Subscription\Entity\Subscription;
use App\Client\Registration\Form\ClientRegistrationFormType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{

    private const SETTING_CODE = "ADMIN";

    private $flashy;

    public function __construct(FlashyNotifier $flashy)
    {
        $this->flashy = $flashy;
    }
    /**
     * @IsGranted("ROLE_RESPONSABLE")
     * @Route("/client/register", name="app_register_client")
     * 
     * register Allow to register a new customer
     *
     * @param  mixed $request
     * @param  mixed $fileUploader
     * @param  mixed $settingsRepository
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $em, FileUploader $fileUploader, SettingsRepository $settingsRepository): Response
    {
        $registration = new Registration;
        // $registration->setDateOfRegistration(new DateTime('now', new DateTimeZone("UTC")));

        // $date = new DateTime('now', new DateTimeZone("UTC"));

        $registration->setAmountOfRegistration($settingsRepository->findOneBy(["code" => $this::SETTING_CODE])->getDefaultRegistrationAmount());

        $form = $this->createForm(ClientRegistrationFormType::class, $registration);
        $form->handleRequest($request);


        // Ensure to have a default preview and save the path only for image file
        $path = "profil_icon.png";
        $data = $form->get('registeredClient')->get('photoProfil')->getData();
        if ($data != null && str_starts_with($data->getClientMimeType(), "image/")) {
            $path = $data->getClientOriginalName();
        }

        // $path = $this->session->get('photoProfil', '');
        // dump($path);
        // if (!empty($path)) {
        //     if ($data != null && str_starts_with($data->getClientMimeType(), "image/")) {
        //         $path = $data->getClientOriginalName();
        //     }else 
        //     $data = $form->get('registeredClient')->get('photoProfil')->getData();

        // } else {
        //     $path = "profil_icon.png";
        // }
        // dump($data);

        // $this->session->set('photoProfil', $path);
        // $this->session->set('data', $data);
        if ($form->isSubmitted() && $form->isValid()) {

            $client = new Client;
            $client = $form->get('registeredClient')->getData();

            if ($data) {
                $photoProfilName = $fileUploader->upload($data);
                $client->setProfilFileName($photoProfilName);
            }


            $dateReg = clone $registration->getDateOfRegistration();
            date_add($dateReg, new DateInterval("P1Y"));
            $registration->setDeadline($dateReg);

            //Subscribe for the first month
            $subscription = new Subscription;
            $subscription->setStartOfSubs($registration->getDateOfRegistration());

            $dateSubs = clone $subscription->getStartOfSubs();
            date_add($dateSubs, new DateInterval("P1M"));
            $subscription->setEndOfSubs($dateSubs);

            $subscription->setAmountOfSubs($settingsRepository->findOneBy(["code" => $this::SETTING_CODE])->getDefaultSubsAmount());
            $subscription->setSubscribedClient($client);

            //Assign the responsable of registration and subscription
            $subscription->setResponsableOfSubs($this->getUser());
            $registration->setResponsableOfRegistration($this->getUser());

            $client->setMyRegistration($registration);
            $client->setMySubscription($subscription);


            $em->persist($client);
            $em->persist($registration);
            $em->persist($subscription);

            $em->flush();

            $this->flashy->success("Inscription réussie");
            return $this->redirectToRoute("app_client_registration_list");
        }
        return $this->render('client/registration/registration.html.twig', [
            'formClientRegister' => $form->createView(),
            'registration' => $registration,
            'path' => $path
        ]);
    }




    /**
     * @IsGranted("ROLE_RESPONSABLE")
     * @Route("/client/registration/cancel/{id<\d+>}", name="app_client_registration_cancel")
     * 
     * cancel the registration of a customer
     *
     * @return Response
     */
    public function cancel(Client $client, Request $request, ClientRepository $clientRepository, EntityManagerInterface $em): Response
    {
        $em->remove($client->getMyRegistration());
        $em->flush();
        $this->flashy->info("Résiliation accomplie !!");

        return $this->redirectToRoute("app_client_registration_list");
    }
    /**
     * @IsGranted("ROLE_RESPONSABLE")
     * @Route("/client/registration/list/{showOnlyRegistered}",name="app_client_registration_list")
     * list of all registred customers
     *
     * @param  mixed $clientRepository
     * @return Response
     */
    public function listOfRegistration(bool $showOnlyRegistered = false, ClientRepository $clientRepository): Response
    {
        $checked = "";
        if ($showOnlyRegistered) {
            $clients = $clientRepository->findOnlyRegistered();
            $checked = "checked";
        } else {
            $clients = $clientRepository->findAll();
        }
        return $this->render("client/registration/list.html.twig", [
            'clients' => $clients,
            'checked' => $checked
        ]);
    }
}
