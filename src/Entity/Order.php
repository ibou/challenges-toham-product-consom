<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Order
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Groups({"read"})
     */
    private UuidInterface $id;

    /**
     * @ORM\Column
     */
    private string $state = 'created';

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;


    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $canceledAt = null;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Customer $customer;

    /**
     * @ORM\OneToMany(targetEntity="OrderLine", mappedBy="order", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    private Collection $lines;

    /**
     * @ORM\ManyToOne(targetEntity="Farm")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Farm $farm;


    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $refusedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->lines = new ArrayCollection();
    }


    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return self
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return Order
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): Order
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCanceledAt(): ?\DateTimeImmutable
    {
        return $this->canceledAt;
    }

    /**
     * @param \DateTimeImmutable|null $canceledAt
     * @return Order
     */
    public function setCanceledAt(?\DateTimeImmutable $canceledAt): Order
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Order
     */
    public function setCustomer(Customer $customer): Order
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @return int
     */
    public function getNumberOfProducts(): int
    {
        return array_sum($this->lines->map(fn(OrderLine $line) => $line->getQuantity())->toArray());
    }

    /**
     * @return float
     */
    public function getTotalIncludingTaxes(): float
    {
        return array_sum($this->lines->map(fn(OrderLine $line) => $line->getPriceIncludingTaxes())->toArray());
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getRefusedAt(): ?\DateTimeImmutable
    {
        return $this->refusedAt;
    }

    /**
     * @param \DateTimeImmutable|null $refusedAt
     */
    public function setRefusedAt(?\DateTimeImmutable $refusedAt): void
    {
        $this->refusedAt = $refusedAt;
    }


    /**
     * @return Farm
     */
    public function getFarm(): Farm
    {
        return $this->farm;
    }

    /**
     * @param Farm $farm
     */
    public function setFarm(Farm $farm): void
    {
        $this->farm = $farm;
    }
}
