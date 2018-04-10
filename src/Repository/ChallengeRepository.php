<?php

namespace App\Repository;

use App\Entity\Challenge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Challenge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Challenge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Challenge[]    findAll()
 * @method Challenge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChallengeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Challenge::class);
    }

//    /**
//     * @return Challenge[] Returns an array of Challenge objects
//     */

    public function findByRef($ref)
    {
        return $this->createQueryBuilder('a')
            ->where('a.ref = :ref')->setParameter('ref', $ref)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


}
