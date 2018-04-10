<?php
/**
 * Created by PhpStorm.
 * User: Hugo LIEGEARD
 * Date: 22/02/2018
 * Time: 17:28
 */

namespace App\Controller;

use App\Entity\CategoriesRecipes;
use App\Entity\Challenge;
use App\Entity\Level;
use App\Entity\Orders;
use App\Entity\Recette;
use App\Entity\Recipes;
use App\Entity\Review;
use App\Entity\Roles;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\AddressHasUser;
use App\Entity\UserChallenge;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


Trait Helper
{
    /**
     * Permet de générer un Slug à partir d'un String
     * @param $text
     * @return String Slug
     * @see https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     */
    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


    // -------------------------------------------------------- SAVE

    /**
     * Sauvegarde en BDD
     * @param $value
     * @return String Slug
     */
    public function save($value)
    {
        # Insertion en BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($value);
        $em->flush();

        return $value;
    }




    // -------------------------------------------------------- Set Auteur

    /**
     * Set l'auteur
     * @param $_POST
     * @param $id
     * @return String Slug
     */
    public function newAuteur($form)
    {
        # Création d'un nouvel utilisateur
        $auteur = new User();


        # Récupération du role
        $role = $this->getDoctrine()
            ->getRepository(Roles::class)
            ->find(2);

        # Récupération des données
            $auteur->setMail($form['mail']);
            $auteur->setName($form['name']);
            $auteur->setPass($form['pass']);
            $auteur->setRole($role);
            $auteur->setXp(0);
            $auteur->setLevel($this->getDoctrine()->getRepository(Level::class)->find(1));

            $auteur->setAvatar('images/avatars/default_avatar.jpg');
            $auteur->setFirstname('');


        return $auteur;
    }




    // -------------------------------------------------------- Initiate Session

    /**
     * Sauvegarde en BDD
     * @param $auteur
     * @return String Slug
     */

    public function initiateSession($auteur)
    {
        # Récupération des variables de session
        $session = new Session();

        $session->set('userName', $auteur->getName());
        $session->set('userId', $auteur->getId());
        $session->set('template', 'template-01');

        return $session;
    }



    // -------------------------------------------------------- New UserChallenge

    /**
     * Sauvegarde en BDD
     * @param
     * @return String Slug
     */

    public function newUserChallenge($auteur)
    {

        # Récupération des challenges
        $challenges = $this->getDoctrine()
            ->getRepository(Challenge::class)
            ->findAll();

        foreach ($challenges as $challenge) {
            # Création d'un nouvel userChallenge
            $userChallenge = new UserChallenge();

            $userChallenge->setUser($auteur);
            $userChallenge->setChallenge($challenge);
            $userChallenge->setAccomplissement(0.0);
            $userChallenge->setUsed(false);

            $this->save($userChallenge);
        }

        return $userChallenge;
    }



    // -------------------------------------------------------- Get This User

    /**
     * Récupérer l'utilisateur de session
     * @return class User
     */

    public function getThisUser()
    {
        $session = $this->get('session');

        # Récupération de l'ID de l'auteur
        $auteurId = $session->get('userId');

        # Recherche de l'Auteur de la recette
        $auteur = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($auteurId);

        return $auteur;
    }




    // -------------------------------------------------------- formEditor

    /**
     * Formulaire de création de recette
     * @return Form $form
     */

    public function formEditor()
    {
        # Création d'une nouvelle recette
        $recette = new Recipes();

        # Créer le formuaire permettant l'ajout d'une recette
        $form = $this->createFormBuilder($recette)

            ->add('titre', TextType::class, [
                'required'      => true,
                'label'         => false,
                'attr'          => [
                    'placeholder'   => 'Titre de votre recette...',
                    'class'         => 'form-control'
                ]
            ])

            ->add('description', TextType::class, [
                'required'      => true,
                'label'         => false,
                'attr'          => [
                    'placeholder'   => 'Description',
                    'class'         => 'form-control'
                ]
            ])

//            ->add('categories_recipes', ChoiceType::class, [
//                'choices' => [
//                    $categories[0]->getNamesCategoriesRecipes() => $categories[0]->getId(),
//                    $categories[1]->getNamesCategoriesRecipes() => $categories[1]->getId(),
//                    $categories[2]->getNamesCategoriesRecipes() => $categories[2]->getId(),
//                    $categories[3]->getNamesCategoriesRecipes() => $categories[3]->getId(),
//                ],
//                'required'  => true,
//                'label'     => false,
//                'attr'      =>  [
//                    'class' => 'form-control'
//                ]
//            ])

            ->add('image', FileType::class, [
                'required'  => false,
                'label'     => false,
                'data_class' => null,
                'attr'          => [
                    'class'         => 'form-control'
                ]
            ])

            ->add('price', MoneyType::class, [
                'required'      => false,
                'currency'      => 'EUR',
                'label'         => false,
                'empty_data'    => 'Price',
                'attr'          => [
                    'placeholder'   => 'Prix € ',
                    'class'         => ''
                ]
            ])

            ->add('quantity', IntegerType::class, [
                'required'      => false,

                'label'         => false,
                'empty_data'    => 1,
                'attr'          => [
                    'placeholder'   => 'quantité',
                    'class'         => ''
                ]
            ])

            ->add('hour', TimeType::class, array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr'          => [
                    'placeholder'   => 'Plage horraire...',
                    'class'         => ''
                ]
            ))


            ->add('submit', SubmitType::class, [
                'label'         => false,
                'attr'          => [
                    'class'         => 'btn btn-primary'
                ]
            ])

            ->getForm();

        return $form;
    }



    // -------------------------------------------------------- saveImage

    /**
     * Sauvegarde une image en BDD
     * @return class User
     */

    public function saveImage($recette)
    {
        # Récupération de l'image
        $image = $recette->getImage();

        # String Aléatoire
        $chaine  = rand(1000000, 99999999);

        # Nom du fichier
        $fileName = $chaine.'.'.$image->guessExtension();

        $image->move(
            $this->getParameter('recettes'),
            $fileName
        );

        $recette->setImage('images/recettes/' . $fileName);

        return $recette;
    }



    // -------------------------------------------------------- formAddress

    /**
     * Formulaire de l'address
     * @return Form $form
     */

    public function formAddress()
    {
        // On crée une nouvelle address
        $address = new Address();

        # Créer le formuaire permettant l'ajout d'un utilisateur
        $formBuilder = $this->createFormBuilder($address);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('street', TextType::class)
            ->add('zip_code', IntegerType::class)
            ->add('city', TextType::class)
            ->add('number', IntegerType::class)
            ->add('comment', TextType::class)
            ->add('save', SubmitType::class);
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        return $form;
    }



    // -------------------------------------------------------- setAddress

    /**
     * Sauvegarde Address
     * @param $post
     * @return Address $address
     */

    public function setAddress($post)
    {
        $address = new Address();

        # Récupération des données
        $address->setStreet($post['street']);
        $address->setZipCode($post['zip_code']);
        $address->setCity($post['city']);
        $address->setNumber($post['number']);
        $address->setComment($post['comment']);
        $address->setLat($_COOKIE['lat']);
        $address->setLng($_COOKIE['lng']);
        $address->setIp($_COOKIE['ip']);

        $this->save($address);

        return $address;
    }



    // -------------------------------------------------------- setUserChallenge

    /**
     * Sauvegarde en BDD
     * @param   $auteur
     * @param   $challenges
     * @return String
     */

    public function setUserChallenge($auteur, $challenges)
    {
        foreach ($challenges as $challenge) {

            $userChallenge = $this->getDoctrine()
                ->getRepository(UserChallenge::class)
                ->findThisOne($auteur, $challenge);

            // ******** Récupération des variables
            $max = $challenge->getMax();
            $accomplissement = $userChallenge->getAccomplissement();



            // ******** Calculs
            $accomplissement = round($accomplissement * $max + 1) / $max;



            // ******** Traitement
            if($accomplissement > 1) {
                $accomplissement = 1.0;
            }

            $userChallenge->setAccomplissement($accomplissement);

        }

        return '';
    }




    // -------------------------------------------------------- setOrder

    /**
     * Sauvegarde en BDD de la commande
     * @param $recette
     * @return Orders $order
     */

    public function setOrder($recette)
    {
        // Création d'une nouvelle order
        $order = new Orders();

        // Récupération de l'utilisateur de Session
        $user = $this->getThisUser();


        // Modification de order
        $order->setRecipes($recette);
        $order->setUser($user);
        $order->setQuantities($_POST['quantity']);
        $order->setCancel(false);


        // ***************************************************

        // Récupération du status de la recette
        $status = $this->getDoctrine()
            ->getRepository(Status::class);

        if ($recette->getQuantity() - $_POST['quantity'] <= 0) :
            $recette->setStatus($status->find(1));
        endif;

        $recette->setQuantity($recette->getQuantity() - $_POST['quantity']);

        $this->save($recette);


        // ***************************************************




        return $order;
    }











}