<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SaleRepository::class)
 */
class Sale
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
    private $dateOfSale;

    /**
     * @ORM\Column(type="integer")
     */
    private $previousStock;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantitySold;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mySales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsableOfSale;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="sales")
     * @ORM\JoinColumn(nullable=false)
     */
    private $articleSold;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfSale(): ?\DateTimeInterface
    {
        return $this->dateOfSale;
    }

    public function setDateOfSale(\DateTimeInterface $dateOfSale): self
    {
        $this->dateOfSale = $dateOfSale;

        return $this;
    }

    public function getPreviousStock(): ?int
    {
        return $this->previousStock;
    }

    public function setPreviousStock(int $previousStock): self
    {
        $this->previousStock = $previousStock;

        return $this;
    }

    public function getQuantitySold(): ?int
    {
        return $this->quantitySold;
    }

    public function setQuantitySold(int $quantitySold): self
    {
        $this->quantitySold = $quantitySold;

        return $this;
    }

    public function getResponsableOfSale(): ?User
    {
        return $this->responsableOfSale;
    }

    public function setResponsableOfSale(?User $responsableOfSale): self
    {
        $this->responsableOfSale = $responsableOfSale;

        return $this;
    }

    public function getArticleSold(): ?Article
    {
        return $this->articleSold;
    }

    public function setArticleSold(?Article $articleSold): self
    {
        $this->articleSold = $articleSold;

        return $this;
    }
}
