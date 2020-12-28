<?php


namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * Class UserRepository
 * @package App\Repository
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method User|null findOneByEmail(string $email): ?User
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    /**
     * @param Uuid $token
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserByForgottenPass(Uuid $token): ?User
    {
        return $this->createQueryBuilder("u")
            ->where("u.forgottenPassword.token = :token")
            ->setParameter("token", $token)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
