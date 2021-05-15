<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startOfService;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $endOfService;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartOfService(): ?\DateTimeInterface
    {
        return $this->startOfService;
    }

    public function setStartOfService(\DateTimeInterface $startOfService): self
    {
        $this->startOfService = $startOfService;

        return $this;
    }

    public function getEndOfService(): ?\DateTimeInterface
    {
        return $this->endOfService;
    }

    public function setEndOfService(\DateTimeInterface $endOfService): self
    {
        $this->endOfService = $endOfService;

        return $this;
    }

    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    public function setResponsable(?User $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }
}
