<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Registration;
use App\Entity\Subscription;
use App\Form\ClientFormType;
use App\Service\FileUploader;
use App\Form\ClientRegisterFormType;
use App\Form\ClientRegistrationFormType;
use App\Repository\SettingsRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
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
        $registration->setDateOfRegistration(new DateTime('now', new DateTimeZone("UTC")));

        $date = new DateTime('now', new DateTimeZone("UTC"));
        date_add($date, new DateInterval("P1Y"));
        $registration->setDeadline($date);

        $registration->setAmountOfRegistration($settingsRepository->findOneBy(["code" => $this::SETTING_CODE])->getDefaultRegistrationAmount());

        $form = $this->createForm(ClientRegistrationFormType::class, $registration);
        $form->handleRequest($request);


        // Ensure to have a default preview and save the path only for image file
        $path = "profil_icon.png";
        $data = $form->get('registeredClient')->get('photoProfil')->getData();
        if ($data != null && str_starts_with($data->getClientMimeType(), "image/")) {
            $path = $data->getClientOriginalName();
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $client = new Client;
            $client = $form->get('registeredClient')->getData();
            
            $photoProfil = $form->get('registeredClient')->get('photoProfil')->getData();
            if ($photoProfil) {
                $photoProfilName = $fileUploader->upload($photoProfil);
                $client->setProfileFileName($photoProfilName);
            }
            
            //auto subscription
            $subscription = new Subscription;
            $subscription->setStartOfSubs($registration->getDateOfRegistration());
            
            $dateSubs = new DateTime('now', new DateTimeZone("UTC"));
            date_add($dateSubs, new DateInterval("P1M"));
            $subscription->setEndOfSubs($dateSubs);
            
            $subscription->setAmountOfSubs($settingsRepository->findOneBy(["code" => $this::SETTING_CODE])->getDefaultSubsAmount());
            $subscription->setSubscribedClient($client);
            
            //Assign the responsable of registration and subscription
            $subscription->setResponsableOfSubs($this->getUser());
            $registration->setResponsableOfRegistration($this->getUser());
            
            $client->setMyRegistration($registration);
            $client->setMySubscription($subscription);

            $em -> persist($client);
            $em -> persist($registration);
            $em -> persist($subscription);

            $em->flush();
            
            $this->flashy->success("Inscription réussie");
            return $this->redirectToRoute("app_home");
            
        }
        return $this->render('client/registration.html.twig', [
            'formClientRegister' => $form->createView(),
            'registration' => $registration,
            'path' => $path
        ]);
    }
}
