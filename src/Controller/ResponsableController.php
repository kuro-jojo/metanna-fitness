<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResponsableType;
use App\Security\EmailVerifier;
use App\Form\ResponsableEditType;
use App\Repository\UserRepository;
use Flasher\Prime\FlasherInterface;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ResponsableActivityTracker;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class ResponsableController extends AbstractController
{

    private  const  ROLE_RESPONSABLE = "ROLE_RESPONSABLE";

    private  const  RESPONSABLE_ADD_ACTIVITY = "Ajout d'un responsable";
    private  const  RESPONSABLE_RIGHT_MODIFY_ACTIVITY = "Modification des droits d'un responsable";
    private  const  RESPONSABLE_LIST_ACTIVITY = "Consulation des responsables";
    private  const  RESPONSABLE_PROFILE_EDIT_ACTIVITY = "Edition du profil";

    private  const   ROLE_RIGHT_REGISTER_CLIENT = "ROLE_RIGHT_REGISTER_CLIENT";
    private  const   ROLE_RIGHT_CANCEL_REGISTRATION = "ROLE_RIGHT_CANCEL_REGISTRATION";
    private  const   ROLE_RIGHT_LIST_REGISTRATION = "ROLE_RIGHT_LIST_REGISTRATION";
    private  const   ROLE_RIGHT_EDIT_REGISTRATION = "ROLE_RIGHT_EDIT_REGISTRATION";
    private  const   ROLE_RIGHT_SUBSCRIBE_CLIENT = "ROLE_RIGHT_SUBSCRIBE_CLIENT";
    private  const   ROLE_RIGHT_LIST_SUBSCRIPTION = "ROLE_RIGHT_LIST_SUBSCRIPTION";
    private  const   ROLE_RIGHT_SELL_ARTICLE = "ROLE_RIGHT_SELL_ARTICLE";
    private  const   ROLE_RIGHT_SALES_HISTORY = "ROLE_RIGHT_SALES_HISTORY";
    private  const   ROLE_RIGHT_SALES_MANAGEMENT = "ROLE_RIGHT_SALES_MANAGEMENT";
    private  const   ROLE_RIGHT_ADD_RESPONSABLE = "ROLE_RIGHT_ADD_RESPONSABLE";
    private  const   ROLE_RIGHT_LIST_RESPONSABLE = "ROLE_RIGHT_LIST_RESPONSABLE";
    private  const   ROLE_RIGHT_RESPONSABLE_ACTIVITIES = "ROLE_RIGHT_RESPONSABLE_ACTIVITIES";

    private $emailVerifier;
    private $flasher;
    private $em;
    private $passwordEncoder;
    private $userRepository;
    private $responsableTracker;


    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $em, FlasherInterface $flasher, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, ResponsableActivityTracker $responsableTracker)
    {
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
        $this->flasher = $flasher;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->responsableTracker = $responsableTracker;
    }

    #[Route('/responsable/register', name: 'app_responsable_register')]
    /**
     * @Security("is_granted('ROLE_RIGHT_ADD_RESPONSABLE') or is_granted('ROLE_ADMIN')")
     * 
     */
    public function addResponsable(Request $request): Response
    {
        $user = new User();

        // List of rights


        $form = $this->createForm(ResponsableType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $rights = $request->request->get("rightDefinition");
            if (empty($rights)) {

                return $this->render('responsable/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'rights' => $this->getRights(),
                    'error' => 'Sélectionnez au moins un droit!!'
                ]);
            } else {


                // encode the plain password
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $user->setRoles($rights);

                $this->em->persist($user);
                $this->em->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('kurojojo08@gmail.com', 'Phantom Bot'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('responsable/confirmation_email.html.twig')
                );
                // do anything else you need here, like send an email
                $this->flasher->addInfo("Le mail de confirmation a été envoyé");
                $this->responsableTracker->saveTracking($this::RESPONSABLE_ADD_ACTIVITY, $this->getUser());

                return $this->redirectToRoute('app_responsable_register');
            }
        }

        return $this->render('responsable/register.html.twig', [
            'registrationForm' => $form->createView(),
            'rights' => $this->getRights()
        ]);
    }

    #[Route('/responsable/list', name: 'app_responsable_list')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_LIST_RESPONSABLE') or is_granted('ROLE_ADMIN')")
     * 
     * list all responsables
     *
     * @return Response
     */
    public function list(): Response
    {
        $responsables = $this->userRepository->findByIsVerified(true);

        foreach ($responsables as $key => $responsable) {
            if (in_array("ROLE_ADMIN", $responsable->getRoles())) {
                unset($responsables[$key]);
            }
        }
        $this->responsableTracker->saveTracking($this::RESPONSABLE_LIST_ACTIVITY, $this->getUser());

        return $this->render('responsable/list.html.twig', [
            "responsables" => $responsables,
            'rights' => $this->getRights(),
        ]);
    }

    #[Route('/responsable/editProfile', name: 'app_responsable_edit_profile')]
    /**
     * @IsGranted("ROLE_RESPONSABLE")
     *  
     * editProfile
     *
     * @return Response
     */
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ResponsableEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData() != null) {

                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $this->em->flush();
            $this->flasher->addSuccess('Compte modifié avec succès');

            $this->responsableTracker->saveTracking($this::RESPONSABLE_PROFILE_EDIT_ACTIVITY, $this->getUser());

            return $this->redirectToRoute('app_home');
        }
        return $this->render('responsable/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/responsable/{id<\d+>}/editRight', name: 'app_responsable_edit_right')]
    /**
     * edit responsible right
     *
     * @param  mixed $user
     * @return void
     */
    public function editRight(User $user, Request $request): Response
    {
        $rights = $request->request->all();

        if (!empty($rights)) {

            array_push($rights['rightDefinition'], $this::ROLE_RESPONSABLE);
            $user->setRoles($rights['rightDefinition']);
            $this->em->flush();

            $this->flasher->addInfo("Les nouveaux droits ont été affectés");
            $this->responsableTracker->saveTracking($this::RESPONSABLE_RIGHT_MODIFY_ACTIVITY, $this->getUser());

            return $this->redirectToRoute('app_responsable_list');
        }

        return $this->render('responsable/edit_right.html.twig', [
            'responsable' => $user,
            'rights' => $this->getRoles()
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    /**
     * verifyUserEmail
     *
     * @param  mixed $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        $id = $request->get('id');
        if (null === $id) {
            return $this->redirectToRoute('app_responsable_register');
        }

        $user = $this->userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_responsable_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_responsable_register');
        }

        // @TODO Change the redirect on addS and handle or remove the flash message in your templates
        $this->flasher->addSuccess('Votre adresse email a bien été vérifiée.');

        $roles = $user->getRoles();
        dump($user->getRoles());
        array_push($roles, $this::ROLE_RESPONSABLE);
        $user->setRoles($roles);

        $this->em->flush();
        return $this->redirectToRoute('app_login');
    }


    public function getRights()
    {
        return [
            "Inscription" => [
                $this::ROLE_RIGHT_REGISTER_CLIENT => "Inscrire un client",
                $this::ROLE_RIGHT_CANCEL_REGISTRATION => "Résilier une inscription",
                $this::ROLE_RIGHT_LIST_REGISTRATION => "Consulter les inscriptions",
                $this::ROLE_RIGHT_EDIT_REGISTRATION => "Edition d'une inscription/client",

            ],
            "Abonnement" => [
                $this::ROLE_RIGHT_SUBSCRIBE_CLIENT => "Abonner un client",
                $this::ROLE_RIGHT_LIST_SUBSCRIPTION => "Consulter les abonnements",
            ],
            "Produits" => [
                $this::ROLE_RIGHT_SELL_ARTICLE => "Vendre un produit",
                $this::ROLE_RIGHT_SALES_HISTORY => "Historique des ventes",
                $this::ROLE_RIGHT_SALES_MANAGEMENT => "Gestion des articles",
            ],
            "Administration" => [
                $this::ROLE_RIGHT_ADD_RESPONSABLE => "Ajouter un responsable",
                $this::ROLE_RIGHT_LIST_RESPONSABLE => "Consulter les responsables",
                $this::ROLE_RIGHT_RESPONSABLE_ACTIVITIES => "Tracking des responsables",
            ]

        ];
    }
}
