<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\Recipes;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Orders::class);
    }


    /**
     * @param User $user
     * @param Boolean $cancel
     */
    public function findByUser($user, $cancel = false)
    {
        if($cancel) :
            return $this->createQueryBuilder('a')
                ->where('a.user = :user')->setParameter('user', $user)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->getResult();
        else :
            return $this->createQueryBuilder('a')
                ->where('a.user = :user')->setParameter('user', $user)
                ->andWhere('a.cancel = :cancel')->setParameter('cancel', $cancel)
                ->orderBy('a.id', 'DESC')
                ->getQuery()
                ->getResult();
        endif;
    }



    /**
     * @param Recipes $recipes
     * @param Boolean $cancel
     * @return Orders $ventes
     */
    public function findByRecipes($recipes, $cancel = false)
    {
        $ventes = [];

        foreach ($recipes as $recipe) {
            if($cancel) :
                $ventes = $this->createQueryBuilder('a')
                    ->where('a.recipes = :recipes')->setParameter('recipes', $recipe)
                    ->orderBy('a.id', 'DESC')
                    ->getQuery()
                    ->getResult();
            else :
                $ventes =  $this->createQueryBuilder('a')
                    ->where('a.recipes = :recipes')->setParameter('recipes', $recipe)
                    ->andWhere('a.cancel = :cancel')->setParameter('cancel', $cancel)
                    ->orderBy('a.id', 'DESC')
                    ->getQuery()
                    ->getResult();
            endif;
        }

        return $ventes;

    }


    /**
    * @param User $user
     * @return Recipes $plats
    */
    public function findPlatsByUser($user)
    {
        foreach ($this->findByUser($user) as $order) {
            $plats[] = $order->getRecipes();
        }

        return $plats;
    }
}
