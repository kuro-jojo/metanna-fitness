<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $defaultRegistrationAmount;

    /**
     * @ORM\Column(type="integer")
     */
    private $defaultSubsAmount;

    /**
     * @ORM\Column(type="string", length=10,unique=true)
     */
    private $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDefaultRegistrationAmount(): ?int
    {
        return $this->defaultRegistrationAmount;
    }

    public function setDefaultRegistrationAmount(int $defaultRegistrationAmount): self
    {
        $this->defaultRegistrationAmount = $defaultRegistrationAmount;

        return $this;
    }

    public function getDefaultSubsAmount(): ?int
    {
        return $this->defaultSubsAmount;
    }

    public function setDefaultSubsAmount(int $defaultSubsAmount): self
    {
        $this->defaultSubsAmount = $defaultSubsAmount;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
