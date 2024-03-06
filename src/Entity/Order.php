<?php

namespace App\Entity;

use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $selected_products = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryDay = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryHour = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(type: Types::JSON)]
    private ?array $selected_formule = null;

    #[ORM\Column]
    private ?bool $state = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripe_session_id = null;

    #[ORM\Column(nullable: true)]
    private ?float $selected_formule_price = null;

    public function __toString()
    {
        return $this->getSelectedProducts();
        return $this->getSelectedFormule();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDeliveryDay(): ?string
    {
        return $this->deliveryDay;
    }

    public function setDeliveryDay(string $deliveryDay): static
    {
        $this->deliveryDay = $deliveryDay;

        return $this;
    }

    public function getDeliveryHour(): ?string
    {
        return $this->deliveryHour;
    }

    public function setDeliveryHour(string $deliveryHour): static
    {
        $this->deliveryHour = $deliveryHour;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

 
    
        public function getSelectedFormule(): ?array
        {
            return $this->selected_formule;
        }
    
        public function setSelectedFormule(?array $selected_formule): static
        {
            $this->selected_formule = $selected_formule;
    
            return $this;
        }
    

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripe_session_id;
    }

    public function setStripeSessionId(string $stripe_session_id): static
    {
        $this->stripe_session_id = $stripe_session_id;

        return $this;
    }

    public function getSelectedFormulePrice(): ?float
    {
        return $this->selected_formule_price;
    }

    public function setSelectedFormulePrice(float $selected_formule_price): static
    {
        $this->selected_formule_price = $selected_formule_price;

        return $this;
    }   
}
