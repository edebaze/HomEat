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


    public function FindNear($value)
    {
        $adresses = $this->findAll();
        $users = [];

        foreach ($adresses as $address) {
            $distance = $this->Distance($address->getAddress()->getLat(), $address->getAddress()->getLng(), $_COOKIE['lat'], $_COOKIE['lng']);

            if($distance < $value) {
                $users[] = $address->getUser();
            }
            dump($distance);
        }


        return $users;
    }


    public function convertRad($input)
    {
        return (pi() * $input)/180;
    }


    public function Distance($lat_a_degre, $lon_a_degre, $lat_b_degre, $lon_b_degre)
    {

        $R = 6378; //Rayon de la terre en Kilomètres

        $lat_a = $this->convertRad($lat_a_degre);
        $lon_a = $this->convertRad($lon_a_degre);
        $lat_b = $this->convertRad($lat_b_degre);
        $lon_b = $this->convertRad($lon_b_degre);

        $d = $R * (pi()/2 - asin( sin($lat_b) * sin($lat_a) + cos($lon_b - $lon_a) * cos($lat_b) * cos($lat_a)));
        return $d;
    }
}
