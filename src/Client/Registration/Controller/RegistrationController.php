<?php

namespace App\Client\Registration\Controller;

use DateInterval;
use App\Client\Entity\Client;
use App\Service\FileUploader;
use Flasher\Prime\FlasherInterface;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Client\Repository\ClientRepository;
use App\Service\ResponsableActivityTracker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Client\Registration\Entity\Registration;
use App\Client\Subscription\Entity\Subscription;
use App\Client\Registration\Form\ClientRegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{

    private const SETTING_CODE = "ADMIN";
    private const REGISTRATION_ACTIVITY = "Inscription d'un client";
    private const REGISTRATION_CANCEL_ACTIVITY = "Résiliation d'une inscription";
    private const REGISTRATION_LIST_ACTIVITY = "Visualisation des clients inscrits";

    private $flasher;
    private $clientRepository;
    private $em;
    private $responsableTracker;

    public function __construct(FlasherInterface $flasher, ClientRepository $clientRepository, EntityManagerInterface $em,ResponsableActivityTracker $responsableTracker)
    {
        $this->flasher = $flasher;
        $this->clientRepository = $clientRepository;
        $this->em = $em;
        $this->responsableTracker = $responsableTracker;
    }

    #[Route('/client/register', name: 'app_register_client')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_REGISTER_CLIENT') or is_granted('ROLE_ADMIN')")
     * 
     * register Allow to register a new customer
     *
     * @param  mixed $request
     * @param  mixed $fileUploader
     * @param  mixed $settingsRepository
     * @return Response
     */
    public function register(Request $request, FileUploader $fileUploader, SettingsRepository $settingsRepository): Response
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
                $photoProfilName = $fileUploader->upload($data, 'profil');
                $client->setProfilFileName($photoProfilName);
            }

            // Remove the 00221 of the phone number
            if (preg_match('/^(00221)/', $client->getTelephone())) {
                $client->setTelephone(substr($client->getTelephone(), 5));
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

            // update user entity 
            // $user = $this->getUser();
            // $user->addRegistrationsRealized($registration);
            // $user->addSubsRealized($subscription);

            $this->em->persist($client);
            $this->em->persist($registration);
            $this->em->persist($subscription);

            $this->em->flush();


            // Save actitity 
            $this->responsableTracker->saveTracking($this::REGISTRATION_ACTIVITY,$this->getUser());

            $this->flasher->addSuccess("Inscription réussie");
            return $this->redirectToRoute("app_client_registration_list");
        }
        return $this->render('client/registration/registration.html.twig', [
            'formClientRegister' => $form->createView(),
            'registration' => $registration,
            'path' => $path
        ]);
    }


    #[Route('/client/registration/cancel/{id<\d+>}', name: 'app_client_registration_cancel')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_CANCEL_REGISTRATION') or is_granted('ROLE_ADMIN')")
     * 
     * cancel the registration of a customer
     *
     * @param  mixed $client
     * @return Response
     */
    public function cancel(Client $client): Response
    {
        $this->responsableTracker->saveTracking($this::REGISTRATION_CANCEL_ACTIVITY,$this->getUser());

        $this->em->remove($client->getMyRegistration());
        $this->em->remove($client->getMySubscription());
        $this->em->flush();
        $this->flasher->addInfo("Résiliation accomplie !!");


        return $this->redirectToRoute("app_client_registration_list");
    }

    #[Route('/client/registration/list/{showOnlyRegistered}', name: 'app_client_registration_list')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_LIST_REGISTRATION') or is_granted('ROLE_ADMIN')")
     * 
     * list of all registered customers
     * 
     * @param  mixed $showOnlyRegistered
     * @return Response
     */
    public function listOfRegistration(bool $showOnlyRegistered = false): Response
    {
        $checked = "";
        if ($showOnlyRegistered) {
            $clients = $this->clientRepository->findOnlyRegistered();
            $checked = "checked";
        } else {
            $clients = $this->clientRepository->findAll();
        }
        $this->responsableTracker->saveTracking($this::REGISTRATION_LIST_ACTIVITY,$this->getUser());

        return $this->render("client/registration/list.html.twig", [
            'clients' => $clients,
            'checked' => $checked
        ]);
    }
}
