<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResponsableType;
use App\Security\EmailVerifier;
use App\Form\ResponsableEditType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class ResponsableController extends AbstractController
{

    private  const   ROLE_RESPONSABLE = "ROLE_RESPONSABLE";

    private $emailVerifier;
    private $flashy;
    private $em;
    private $passwordEncoder;
    private $userRepository;


    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $em, FlashyNotifier $flashy, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
        $this->flashy = $flashy;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    #[Route('/responsable/register', name : 'app_responsable_register')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function addResponsable(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(ResponsableType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

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
            $this->flashy->primaryDark("Le mail de confirmation a été envoyé", $this->generateUrl("app_home"));
            return $this->redirectToRoute('app_responsable_register');
        }

        return $this->render('responsable/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/responsable/list', name : 'app_responsable_list')]
    /**
     * @IsGranted("ROLE_ADMIN")
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
        return $this->render('responsable/list.html.twig', [
            "responsables" => $responsables,
        ]);
    }

    #[Route('/responsable/editProfile', name : 'app_responsable_edit_profile')]
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
            $this->flashy->success('Compte modiifié avec succès');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('responsable/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name : 'app_verify_email')]
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

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->flashy->success('Votre adresse email a bien été vérifiée.', $this->generateUrl("app_login"));
        $user->setRoles([$this::ROLE_RESPONSABLE]);
        $this->em->flush();
        return $this->redirectToRoute('app_login');
    }
}
