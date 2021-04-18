<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RegistrationRepository;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=RegistrationRepository::class)
 */
class Registration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateOfRegistration;

    /**
     * @ORM\Column(type="integer")
     */
    public $amountOfRegistration;
    /**
     * @ORM\Column(type="date")
     */
    private $deadline;

    /**
     * @Assert\Valid
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="myRegistration", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $registeredClient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="registrationsRealized")
     */
    private $responsableOfRegistration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfRegistration(): ?\DateTimeInterface
    {
        return $this->dateOfRegistration;
    }

    public function setDateOfRegistration(?\DateTimeInterface $dateOfRegistration): self
    {
        $this->dateOfRegistration = $dateOfRegistration;

        return $this;
    }

    public function getAmountOfRegistration(): ?int
    {
        return $this->amountOfRegistration;
    }

    public function setAmountOfRegistration(int $amountOfRegistration): self
    {
        $this->amountOfRegistration = $amountOfRegistration;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getRegisteredClient(): ?Client
    {
        return $this->registeredClient;
    }

    public function setRegisteredClient(Client $registeredClient): self
    {
        $this->registeredClient = $registeredClient;

        return $this;
    }

    public function getResponsableOfRegistration(): ?User
    {
        return $this->responsableOfRegistration;
    }

    public function setResponsableOfRegistration(?User $responsableOfRegistration): self
    {
        $this->responsableOfRegistration = $responsableOfRegistration;

        return $this;
    }
}
