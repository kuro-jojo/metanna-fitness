<?php

namespace App\Entity;

use App\Repository\ClientCardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientCardRepository::class)
 */
class ClientCard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $barCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarCode(): ?string
    {
        return $this->barCode;
    }

    public function setBarCode(string $barCode): self
    {
        $this->barCode = $barCode;

        return $this;
    }
}
