<?php

namespace App\Entity;

use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $selected_products = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'orderProducts')]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSelectedProducts();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSelectedProducts(): ?string
    {
        return $this->selected_products;
    }

    public function setSelectedProducts(string $selected_products): static
    {
        $this->selected_products = $selected_products;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }   
}
