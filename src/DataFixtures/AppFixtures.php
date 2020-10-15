<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * AppFixtures constructor.
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

        $customer = new Customer();
        $customer->setPassword($this->userPasswordEncoder->encodePassword($customer, "dev"));
        $customer->setFirstName("Jimmy")
            ->setLastName("COLLE")
            ->setEmail("customer@gmail.com")
        ;
        $manager->persist($customer);

        $producer = new Producer();
        $producer->setPassword($this->userPasswordEncoder->encodePassword($producer, "dev"));
        $producer->setFirstName("Jane")
            ->setLastName("Doe")
            ->setEmail("producer@gmail.com")
        ;

        $manager->persist($producer);
        $manager->flush();

        $manager->flush();
    }
}
