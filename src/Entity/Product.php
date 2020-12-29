<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package App\Entity
 * @ORM\Entity
 */
class Product
{

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private Uuid $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    private string $name = "";

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private string $description = "";

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(0)
     */
    private int $quantity = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Farm")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private Farm $farm;

    /**
     * @ORM\Embedded(class="Price")
     * @Assert\Valid
     */
    private ?Price $price = null;

    /**
      * @return Uuid
      */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     */
    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
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
    
    /**
     * @return Price|null
     */
    public function getPrice(): ?Price
    {
        return $this->price;
    }
    
    /**
     * @param Price|null $price
     * @return Product
     */
    public function setPrice(?Price $price): Product
    {
        $this->price = $price;
        
        return $this;
    }
}
