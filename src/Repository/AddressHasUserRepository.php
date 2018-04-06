<?php

namespace App\Repository;

use App\Entity\AddressHasUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AddressHasUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressHasUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressHasUser[]    findAll()
 * @method AddressHasUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressHasUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AddressHasUser::class);
    }

//    /**
//     * @return AddressHasUser[] Returns an array of AddressHasUser objects
//     */
    public function findByUser($value)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
}
