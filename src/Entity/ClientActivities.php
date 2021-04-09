<?php

namespace App\Entity;

use App\Repository\ClientActivitiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientActivitiesRepository::class)
 */
class ClientActivities
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
    private $dateOfActivity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stateOfClient;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="myActivities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfActivity(): ?\DateTimeInterface
    {
        return $this->dateOfActivity;
    }

    public function setDateOfActivity(\DateTimeInterface $dateOfActivity): self
    {
        $this->dateOfActivity = $dateOfActivity;

        return $this;
    }

    public function getStateOfClient(): ?string
    {
        return $this->stateOfClient;
    }

    public function setStateOfClient(string $stateOfClient): self
    {
        $this->stateOfClient = $stateOfClient;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
