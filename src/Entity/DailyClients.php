<?php

namespace App\Entity;

use App\Repository\DailyClientsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DailyClientsRepository::class)
 */
class DailyClients
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
    private $dateOfPresence;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountPay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfPresence(): ?\DateTimeInterface
    {
        return $this->dateOfPresence;
    }

    public function setDateOfPresence(\DateTimeInterface $dateOfPresence): self
    {
        $this->dateOfPresence = $dateOfPresence;

        return $this;
    }

    public function getAmountPay(): ?int
    {
        return $this->amountPay;
    }

    public function setAmountPay(int $amountPay): self
    {
        $this->amountPay = $amountPay;

        return $this;
    }
}
