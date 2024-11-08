<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\OrderStatus;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', enumType: OrderStatus::class)]
    private OrderStatus $status;    
  
    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'orderr')]
    private Collection $orderItems;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;
    private $totalAmount;

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->status = OrderStatus::Preparation;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }



    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setOrderr($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrderr() === $this) {
                $orderItem->setOrderr(null);
            }
        }

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

    public function setStatus(OrderStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }
    public function getStatusAsString(): string
{
    return $this->status->toString();
}

public function calculateTotalAmount(): float
{
    $total = 0;
    foreach ($this->getOrderItems() as $orderItem) {
        $total += $orderItem->getQuantity() * $orderItem->getProductPrice();
    }
    return $total;
}
// src/Entity/Order.php

public function validateOrder(): bool
{
    foreach ($this->orderItems as $orderItem) {
        $product = $orderItem->getProduct();
        
        // Vérification du stock disponible
        if (!$product->isAvailable($orderItem->getQuantity())) {
            return false; // Si le stock n'est pas suffisant, on ne valide pas la commande
        }
    }

    // Mise à jour du stock pour chaque produit commandé
    foreach ($this->orderItems as $orderItem) {
        $product = $orderItem->getProduct();
        $product->setStock($product->getStock() - $orderItem->getQuantity());
    }

    // Mise à jour du statut de la commande
    $this->setStatus(OrderStatus::Validee);

    return true;
}
}