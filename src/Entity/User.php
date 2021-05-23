<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use App\Client\Registration\Entity\Registration;
use App\Client\Subscription\Entity\Subscription;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Cette adresse email est déjà utilisée")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(
     *      message="Veuillez entrer une adresse email")
     * @Assert\Email(
     *      message="L'adresse email est incorrecte")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champ ")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Veuillez remplir ce champ")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Registration::class, mappedBy="responsableOfRegistration")
     */
    private $registrationsRealized;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="responsableOfSubs")
     */
    private $subsRealized;

    /**
     * @ORM\OneToMany(targetEntity=Sale::class, mappedBy="responsableOfSale")
     */
    private $mySales;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="responsable")
     */
    private $services;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    public function __construct()
    {
        $this->registrationsRealized = new ArrayCollection();
        $this->subsRealized = new ArrayCollection();
        $this->mySales = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrationsRealized(): Collection
    {
        return $this->registrationsRealized;
    }

    public function addRegistrationsRealized(Registration $registrationsRealized): self
    {
        if (!$this->registrationsRealized->contains($registrationsRealized)) {
            $this->registrationsRealized[] = $registrationsRealized;
            $registrationsRealized->setResponsableOfRegistration($this);
        }

        return $this;
    }

    public function removeRegistrationsRealized(Registration $registrationsRealized): self
    {
        if ($this->registrationsRealized->removeElement($registrationsRealized)) {
            // set the owning side to null (unless already changed)
            if ($registrationsRealized->getResponsableOfRegistration() === $this) {
                $registrationsRealized->setResponsableOfRegistration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubsRealized(): Collection
    {
        return $this->subsRealized;
    }

    public function addSubsRealized(Subscription $subsRealized): self
    {
        if (!$this->subsRealized->contains($subsRealized)) {
            $this->subsRealized[] = $subsRealized;
            $subsRealized->setResponsableOfSubs($this);
        }

        return $this;
    }

    public function removeSubsRealized(Subscription $subsRealized): self
    {
        if ($this->subsRealized->removeElement($subsRealized)) {
            // set the owning side to null (unless already changed)
            if ($subsRealized->getResponsableOfSubs() === $this) {
                $subsRealized->setResponsableOfSubs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sale[]
     */
    public function getMySales(): Collection
    {
        return $this->mySales;
    }

    public function addMySale(Sale $mySale): self
    {
        if (!$this->mySales->contains($mySale)) {
            $this->mySales[] = $mySale;
            $mySale->setResponsableOfSale($this);
        }

        return $this;
    }

    public function removeMySale(Sale $mySale): self
    {
        if ($this->mySales->removeElement($mySale)) {
            // set the owning side to null (unless already changed)
            if ($mySale->getResponsableOfSale() === $this) {
                $mySale->setResponsableOfSale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setResponsable($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getResponsable() === $this) {
                $service->setResponsable(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

}
