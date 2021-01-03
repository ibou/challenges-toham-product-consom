<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Position;
use App\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $producer = new Producer();
        $producer->setPassword($this->userPasswordEncoder->encodePassword($producer, "dev"));
        $producer->setFirstName("Jane");
        $producer->setLastName("Doe");
        $producer->setEmail("producer@gmail.com");
        $producer->getFarm()->setName("Ferme");
        $address = new Address();
        $address->setAddress("35 rue emile Eudes");
        $address->setZipCode("94140");
        $address->setCity("Alfort-ville");
        $position = new Position();
        $position->setLatitude(48.8);
        $position->setLongitude(2.4167);
        $address->setPosition($position);
        $producer->getFarm()->setAddress($address);
        $manager->persist($producer);
        $manager->flush();

        for ($i = 1; $i <= 19; $i++) {
            $producer = new Producer();
            $producer->setPassword($this->userPasswordEncoder->encodePassword($producer, "dev"));
            $producer->setFirstName("Jane");
            $producer->setLastName("Doe");
            $producer->setEmail("producer+" . $i . "@gmail.com");
            $producer->getFarm()->setName("Ferme");
            $address = new Address();
            $address->setAddress("164 Avenue des Arènes de Cimiez");
            $address->setZipCode("06100");
            $address->setCity("Nice");
            $position = new Position();
            $position->setLatitude(43.7195049426910);
            $position->setLongitude(7.2760391235352);
            $address->setPosition($position);
            $producer->getFarm()->setAddress($address);
            $manager->persist($producer);
            $manager->flush();
        }

        $customer = new Customer();
        $customer->setPassword($this->userPasswordEncoder->encodePassword($customer, "dev"));
        $customer->setFirstName("John");
        $customer->setLastName("Doe");
        $customer->setEmail("customer@gmail.com");
        $manager->persist($customer);

        for ($i = 1; $i <= 19; $i++) {
            $customer = new Customer();
            $customer->setPassword($this->userPasswordEncoder->encodePassword($customer, "dev"));
            $customer->setFirstName("John");
            $customer->setLastName("Doe");
            $customer->setEmail("customer" . $i . "@gmail.com");
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
