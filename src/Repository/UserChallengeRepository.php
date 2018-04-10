<?php

namespace App\Repository;

use App\Entity\UserChallenge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method UserChallenge|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserChallenge|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserChallenge[]    findAll()
 * @method UserChallenge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserChallengeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserChallenge::class);
    }

//    /**
//     * @return UserChallenge[] Returns an array of UserChallenge objects
//     */

    public function findByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')->setParameter('user', $user)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findThisOne($user, $challenge)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :user')->setParameter('user', $user)
            ->andWhere('a.challenge = :challenge')->setParameter('challenge', $challenge)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()[0];
    }

}
