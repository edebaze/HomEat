<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/03/2018
 * Time: 17:21
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }







    //////////////////////////////////////////////////////////////////
    // -------------------------------------------------- LOGINUSER
    //////////////////////////////////////////////////////////////////


    public function loginUser($email, $password)
    {
        $user = $this->createQueryBuilder('a')
            ->where('a.mail = :mail')->setParameter('mail', $email)
            ->andWhere('a.pass = :pass')->setParameter('pass', $password)
            ->getQuery()
            ->getResult();

        if (empty($user)) :
            $email = $this->createQueryBuilder('a')
                ->where('a.mail = :mail')->setParameter('mail', $email)
                ->getQuery()
                ->getResult();
        endif;

        return array($user, $email);


    }


    //////////////////////////////////////////////////////////////////
    // -------------------------------------------------- FINDUSER
    //////////////////////////////////////////////////////////////////


    public function findUser($id_user)
    {
        $user =
            $this->createQueryBuilder('a')
                ->where('a.id = :id_user')->setParameter('id_user', $id_user)
                ->getQuery()
                ->getResult();

        return array($user);
    }


    // ----------------------------------------------------------------------- SAVE


    public function save($auteur, $form, $role)
    {

        # Récupération des données
        $auteur->setMail($form['mail']);
        $auteur->setName($form['name']);
        $auteur->setPass($form['pass']);
        $auteur->setRoles($role);

        $auteur->setAvatar('images/avatars/default_avatar.jpg');
        $auteur->setFirstname('');

    }


    // ----------------------------------------------------------------------- OPEN SESSION

    public function openSession($auteur)
    {
        # Récupération des variables de session
        $session = new Session();

        $session->set('userName', $auteur->getFirstname() . ' ' . $auteur->getName());
        $session->set('userId', $auteur->getId());
        $session->set('template', 'template-01');

        return $session;

    }

}