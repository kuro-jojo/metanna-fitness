<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(
     *      message="Veuillez saisir le nom du client")
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\NotBlank(
     *      message="Veuillez saisir le(s) prénom(s)du client")
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Assert\Regex(
     *      pattern="/^(00221)?(7[786])(\d){7}$/",
     *      message="Respectez le format 77 xxx xx xx ",
     *          )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @Assert\NotBlank(
     *      message="Veuillez entrer la date de naissance")
     * @ORM\Column(type="date",nullable=false)
     */
    private $dateNaissance;

    /*
     * @ORM\Column(type="string", length=255)
     */
    private $profileFileName;

    /**
     * @ORM\OneToOne(targetEntity=Registration::class, mappedBy="registeredClient", cascade={"persist", "remove"})
     */
    private $myRegistration;

    /**
     * @ORM\OneToOne(targetEntity=Subscription::class, mappedBy="subscribedClient", cascade={"persist", "remove"})
     */
    private $mySubscription;

    /**
     * @ORM\OneToMany(targetEntity=ClientActivities::class, mappedBy="client")
     */
    private $myActivities;

    /**
     * @ORM\OneToOne(targetEntity=ClientCard::class, cascade={"persist", "remove"})
     */
    private $myCard;

    public function __construct()
    {
        $this->myActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getProfileFileName(): ?string
    {
        return $this->profileFileName;
    }

    public function setProfileFileName(string $profileFileName): self
    {
        $this->profileFileName = $profileFileName;

        return $this;
    }

    public function getMyRegistration(): ?Registration
    {
        return $this->myRegistration;
    }

    public function setMyRegistration(Registration $myRegistration): self
    {
        // set the owning side of the relation if necessary
        if ($myRegistration->getRegisteredClient() !== $this) {
            $myRegistration->setRegisteredClient($this);
        }

        $this->myRegistration = $myRegistration;

        return $this;
    }

    public function getMySubscription(): ?Subscription
    {
        return $this->mySubscription;
    }

    public function setMySubscription(Subscription $mySubscription): self
    {
        // set the owning side of the relation if necessary
        if ($mySubscription->getSubscribedClient() !== $this) {
            $mySubscription->setSubscribedClient($this);
        }

        $this->mySubscription = $mySubscription;

        return $this;
    }

    /**
     * @return Collection|ClientActivities[]
     */
    public function getMyActivities(): Collection
    {
        return $this->myActivities;
    }

    public function addMyActivity(ClientActivities $myActivity): self
    {
        if (!$this->myActivities->contains($myActivity)) {
            $this->myActivities[] = $myActivity;
            $myActivity->setClient($this);
        }

        return $this;
    }

    public function removeMyActivity(ClientActivities $myActivity): self
    {
        if ($this->myActivities->removeElement($myActivity)) {
            // set the owning side to null (unless already changed)
            if ($myActivity->getClient() === $this) {
                $myActivity->setClient(null);
            }
        }

        return $this;
    }

    public function getMyCard(): ?ClientCard
    {
        return $this->myCard;
    }

    public function setMyCard(?ClientCard $myCard): self
    {
        $this->myCard = $myCard;

        return $this;
    }
}
