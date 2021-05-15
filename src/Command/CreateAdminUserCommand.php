<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-admin-user';
    protected static $defaultDescription = 'Create a new and only admin';

    private $em;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom')
            ->addArgument('prenom', InputArgument::REQUIRED, 'Prenom')
            ->addArgument('email', InputArgument::REQUIRED, 'Email address')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if ($email) {
            $io->note(sprintf('Email: %s', $email));
        }

        $admin = new User;
        
        $admin->setNom($nom);
        $admin->setPrenom($prenom);
        $admin->setEmail($email);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin,$password));

        $admin->setRoles(['ROLE_ADMIN','ROLE_RESPONSABLE']);
        $admin->setIsVerified(true);
        $this->em->persist($admin);
        $this->em->flush();

        $io->success('Administrateur créé avec succès');

        return Command::SUCCESS;
    }
}
