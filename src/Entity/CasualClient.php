<?php

namespace App\Entity;

use App\Repository\CasualClientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CasualClientRepository::class)
 */
class CasualClient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $doneOn;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="casualClients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsableOfRecord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDoneOn(): ?\DateTimeInterface
    {
        return $this->doneOn;
    }

    public function setDoneOn(\DateTimeInterface $doneOn): self
    {
        $this->doneOn = $doneOn;

        return $this;
    }

    public function getResponsableOfRecord(): ?User
    {
        return $this->responsableOfRecord;
    }

    public function setResponsableOfRecord(?User $responsableOfRecord): self
    {
        $this->responsableOfRecord = $responsableOfRecord;

        return $this;
    }
}
