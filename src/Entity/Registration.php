<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

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
    private $amountOfRegistration;

    /**
     * @ORM\Column(type="date")
     */
    private $deadline;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="myRegistration", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $registredClient;

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

    public function setDateOfRegistration(\DateTimeInterface $dateOfRegistration): self
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

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getRegistredClient(): ?Client
    {
        return $this->registredClient;
    }

    public function setRegistredClient(Client $registredClient): self
    {
        $this->registredClient = $registredClient;

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
