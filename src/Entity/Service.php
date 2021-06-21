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
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateOfService;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $serviceName;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of dateOfService
     */
    public function getDateOfService()
    {
        return $this->dateOfService;
    }

    /**
     * Set the value of dateOfService
     */
    public function setDateOfService($dateOfService): self
    {
        $this->dateOfService = $dateOfService;

        return $this;
    }



    /**
     * Get the value of serviceName
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Set the value of serviceName
     */
    public function setServiceName($serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }


    /**
     * Get the value of responsable
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set the value of responsable
     */
    public function setResponsable($responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }
}
