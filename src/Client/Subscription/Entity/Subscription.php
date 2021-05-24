<?php

namespace App\Client\Subscription\Entity;

use App\Entity\User;
use App\Client\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SubscriptionRepository;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
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
    private $startOfSubs;

    /**
     * @ORM\Column(type="date")
     */
    private $endOfSubs;

    /**
     * @ORM\Column(type="integer")
     */
    private $amountOfSubs;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="mySubscription", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscribedClient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subsRealized")
     */
    private $responsableOfSubs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartOfSubs(): ?\DateTimeInterface
    {
        return $this->startOfSubs;
    }

    public function setStartOfSubs(\DateTimeInterface $startOfSubs): self
    {
        $this->startOfSubs = $startOfSubs;

        return $this;
    }

    public function getEndOfSubs(): ?\DateTimeInterface
    {
        return $this->endOfSubs;
    }

    public function setEndOfSubs(\DateTimeInterface $endOfSubs): self
    {
        $this->endOfSubs = $endOfSubs;

        return $this;
    }

    public function getAmountOfSubs(): ?int
    {
        return $this->amountOfSubs;
    }

    public function setAmountOfSubs(int $amountOfSubs): self
    {
        $this->amountOfSubs = $amountOfSubs;

        return $this;
    }

    public function getSubscribedClient(): ?Client
    {
        return $this->subscribedClient;
    }

    public function setSubscribedClient(Client $subscribedClient): self
    {
        $this->subscribedClient = $subscribedClient;

        return $this;
    }

    public function getResponsableOfSubs(): ?User
    {
        return $this->responsableOfSubs;
    }

    public function setResponsableOfSubs(?User $responsableOfSubs): self
    {
        $this->responsableOfSubs = $responsableOfSubs;

        return $this;
    }
}
