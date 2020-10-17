<?php

<<<<<<< HEAD
declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
=======
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
>>>>>>> 4b5f9cc... Update farm info (#8)

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
     */
    private ?UuidInterface $id = null;

    /**
     * @ORM\Column(nullable=true)
     */
    private string $name = "";

    /**
     * @ORM\Column(nullable=true, type="text")
     */
    private ?string $description = null;

    /**
<<<<<<< HEAD
     * @ORM\OneToOne(targetEntity="App\Entity\Producer", mappedBy="farm")
=======
     * @ORM\OneToOne(targetEntity="Producer", mappedBy="farm")
>>>>>>> 4b5f9cc... Update farm info (#8)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Producer $producer;

<<<<<<< HEAD
   

=======
>>>>>>> 4b5f9cc... Update farm info (#8)
    /**
     * @return UuidInterface|null
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
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
}
