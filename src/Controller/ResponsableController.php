<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
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
    

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $em, FlashyNotifier $flashy)
    {
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
        $this->flashy = $flashy;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/responsable/register", name="app_register_responsable")
     */
    public function addResponsable(Request $request, UserPasswordEncoderInterface $passwordEncoder, FlashyNotifier $flashy): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
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
            $flashy->primaryDark("Le responsable a bien reçu le mail de confirmation", $this->generateUrl("app_home"));
            return $this->redirectToRoute('app_register_responsable');
        }

        return $this->render('responsable/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');
        if (null === $id) {
            return $this->redirectToRoute('app_register_responsable');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register_responsable');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_register_responsable');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->flashy->success('Votre adresse email a bien été vérifiée.', $this->generateUrl("app_login"));
        $user->setRoles([$this::ROLE_RESPONSABLE]);
        $this->em->flush();
        return $this->redirectToRoute('app_login');
    }
}
