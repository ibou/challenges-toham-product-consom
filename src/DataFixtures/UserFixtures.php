<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

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
        $manager->persist($producer);

        for ($i = 1; $i <= 4; $i++) {
            $producer = new Producer();
            $producer->setPassword($this->userPasswordEncoder->encodePassword($producer, "dev"));
            $producer->setFirstName("Jane");
            $producer->setLastName("Doe");
            $producer->setEmail("producer+" . $i . "@gmail.com");
            $manager->persist($producer);
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
