<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 */
class CartItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Product $product;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="cart")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Customer $customer = null;


    public function getId(): UuidInterface
    {
        return $this->id;
    }


    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        if ($this->quantity <= 0) {
            $this->customer->getCart()->removeElement($this);
            $this->customer = null;
        }
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
    }

    /**
     * @return float
     */
    public function getPriceIncludingTaxes(): float
    {
        return $this->product->getPriceIncludingTaxes() * $this->quantity;
    }
}
