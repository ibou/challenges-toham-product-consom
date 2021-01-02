<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Farm
 * @package App\Entity
 * @ORM\Entity
 */
class Farm
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
     * @ORM\Column(nullable=true)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private ?string $name = null;

    /**
     * @ORM\Column(nullable=true, type="text")
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private ?string $description = null;

    /**
     * @ORM\OneToOne(targetEntity="Producer", mappedBy="farm")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Producer $producer;

    /**
     * @ORM\Embedded(class="Address")
     * @Assert\Valid
     */
    private ?Address $address = null;

    /**
     * @ORM\Embedded(class="Image")
     * @Assert\Valid
     */
    private Image $image;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Producer
     */
    public function getProducer(): Producer
    {
        return $this->producer;
    }

    /**
     * @param Producer $producer
     */
    public function setProducer(Producer $producer): void
    {
        $this->producer = $producer;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     */
    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }


    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image): void
    {
        $this->image = $image;
    }
}
