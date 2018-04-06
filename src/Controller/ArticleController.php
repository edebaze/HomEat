<?php

namespace App\Controller;

use App\Controller\Helper;
use App\Entity\CategoriesRecipes;
use App\Entity\Challenge;
use App\Entity\Orders;
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


class ArticleController extends Controller
{

    use Helper;



        //////////////////////////////////////////////////////////////////
      // ------------------------------------------------------ SIGNIN
    //////////////////////////////////////////////////////////////////


    /**
     * Formulaire pour ajouter un utilisateur
     * @Route("/signin", name="signin")
     */

    public function signin() {


        // ------------------------------------ Auteur

        # Création d'un nouvel utilisateur
        $auteur = new User();

        # Récupération du role
        $role = $this->getDoctrine()
            ->getRepository(Roles::class)
            ->find(2);

        # Vérification des données du Formulaire
        if (true) :

            $form = $_POST['form'];

            # Récupération des données
            $auteur->setMail($form['mail']);
            $auteur->setName($form['name']);
            $auteur->setPass($form['pass']);
            $auteur->setRoles($role);

            $auteur->setAvatar('images/avatars/default_avatar.jpg');
            $auteur->setFirstname('');


            # Récupération des variables de session
            $session = new Session();

            $session->set('userName', $auteur->getFirstname() . ' ' . $auteur->getName());
            $session->set('userId', $auteur->getId());
            $session->set('template', 'template-01');

            # Insertion en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();


            // ------------------------------------ UserChallenge

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

                # Insertion en BDD
                $em = $this->getDoctrine()->getManager();
                $em->persist($userChallenge);
                $em->flush();
            }



        endif;

        return $this->redirectToRoute('index');
    }




        //////////////////////////////////////////////////////////////////
      // ------------------------------------------------------ LOGIN
    //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/login", name="login")
     */

    public function login() {

            # Initialisation de la variable $message
            $message = '';

            dump($_POST);

            $repository = $this->getDoctrine()
                ->getRepository(User::class);

            # Récupération des données

                # On regarde si l'utilisateur est dans la BDD
                $error = $repository->loginUser($_POST['mail'], $_POST['pass']);

                if(!empty($error[0])) :
                    $session = $repository->openSession($error[0][0]);

                    $user = $this->getDoctrine()
                        ->getRepository(User::class)
                        ->find($session->get('userId'));

                    $recettes = $this->getDoctrine()
                        ->getRepository(Recipes::class)
                        ->findAll();

                    // ------------------------------------------------------------------------------------------------------------------------------------------------------------

                                                            // ------------------------------------------//
                                                            //              ENVOIE DU MAIL               //
                                                            // ------------------------------------------//







                    // ------------------------------------------------------------------------------------------------------------------------------------------------------------


                    # Redirection
                    return $this->redirectToRoute('index', [
                        'user'          => $user,
                        'recettes'      => $recettes
                       ]);

                else :
                    $message = empty($error[1]) ? 'Email invalide' : 'Password invalide';

                    # Affichage du Formulaire dans la Vue
                    return $this->render('index/index.html.twig', [
                        'message'   => $message
                    ]);

                endif;

        # Affichage du Formulaire dans la Vue
        return $this->render('index/index.html.twig', [
            'message'   => $message
        ]);
    }




                //////////////////////////////////////////////////////////////////
              // ----------------------------------------------------- LOGOUT
            //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/logout", name="logout")
     */

    public function logout(SessionInterface $session) {

        # On sauvegarde et ferme la session
        $session->clear();

        # Retour vers l'index
        return $this->redirectToRoute('index');
    }




                //////////////////////////////////////////////////////////////////
              // ---------------------------------------------- MODIFIER MDP
            //////////////////////////////////////////////////////////////////


    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->find($id);

        $form = $this->get('form.factory')->create(UserEditType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $edit = $request->request->get('user_edit');
            if ($edit['password'] == "") {
                $user->setPassword($user->getPassword());
            } else {
                $encoder = $this->container->get('security.password_encoder');
                $newPasswordEncoded = $encoder->encodePassword($user, $edit['password']);
                $user->setPassword($newPasswordEncoded);
            }
            var_dump($edit);
            $em->flush();
            //return $this->redirectToRoute('user_homepage', array());
        }
        return $this->render('UserBundle:User:update.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }




    //////////////////////////////////////////////////////////////////
    // ----------------------------------------------------- EDITOR
    //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/editor", name="editor")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function editor(Request $request) {

        // INITIALISATION RECETTE

        # Initialisation de la variable $message
        $message = '';

        # Création d'une nouvelle recette
        $recette = new Recipes();



        ############ # Récupération de l'auteur # ############

        $session = $this->get('session');

        # Récupération de l'ID de l'auteur
        $auteurId = $session->get('userId');

        # Recherche de l'Auteur de la recette
        $auteur = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($auteurId);

        # Récupération de l'auteur
        $recette->setCuisto($auteur);



        ############ # Récupération de la categorie # ############

        $categories = $this->getDoctrine()
            ->getRepository(CategoriesRecipes::class)
            ->findAll();


        //-------------------- FORMULAIRE --------------------------//

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


        # Traitement des données POST
        $form->handleRequest($request);



        # Vérification des données du Formulaire
        if ($form->isSubmitted()) :

            # Récupération des données
            $recette = $form->getData();

            $categorie = $this->getDoctrine()
                ->getRepository(CategoriesRecipes::class)
                ->find(3);

            $recette->setCategoriesRecipes($categorie);

            dump($recette);
            //die();

            if($recette->getImage() == null) :
                $recette->setImage('images/recettes/default.jpg');
            else :
                # Récupération de l'image
                $image = $recette->getImage();

                # String Aléatoire
                $chaine  = rand(1000000, 99999999);

                # Nom du fichier
                $fileName = $chaine.'.'.$image->guessExtension();

                dump($this);
                //die();

                $image->move(
                    $this->getParameter('recettes'),
                    $fileName
                );

                $recette->setImage('images/recettes/' . $fileName);
            endif;


            dump($recette);
            //die();


            # Insertion en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($recette);
            $em->flush();


            # Redirection vers l'index
            return $this->redirectToRoute('index');

        endif;


        # Affichage du Formulaire dans la Vue
        return $this->render('commun/editor.html.twig', [
            'form'      => $form->createView(),
            'message'   => $message
        ]);
    }




                //////////////////////////////////////////////////////////////////
              // ---------------------------------------------- switchTemplate
            //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/personnalisation", name="personnalisation")
     */

    public function personnalisation(Session $session) {
        $session->set('template', $_POST['template']);
        return $this->redirectToRoute('index');
    }




                //////////////////////////////////////////////////////////////////
              // ---------------------------------------------- searchRecette
            //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/searchRecette", name="searchRecette")
     */

    public function searchRecette() {
        return $this->render('commun/recherche-recette.html.twig', []);
    }






            //////////////////////////////////////////////////////////////////
          // --------------------------------------------- RECHERCHE PLATS
        //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/recherche-plats", name="recherchePlats")
     */

    public function recherchePlats() {
        return $this->render('commun/liste-plats.html.twig', []);
    }




    //////////////////////////////////////////////////////////////////
    // ------------------------------------------------------ AUTEUR
    //////////////////////////////////////////////////////////////////



    /**
     * Recherche auteur
     * @Route("/auteur/{id}",
     *     name="auteur",
     *     requirements={"id" = "\d+"},
     *     methods={"GET"})
     * @param integer $id
     * @return Response
     */

    public function auteur($id) {

        $auteur = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        # Récupération des messages liés à l'auteur
        $reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findByUser($id);

//        $reviews = $this->getDoctrine()
//            ->getRepository(Review::class)
//            ->findByAuteur($id);

        return $this->render('commun/affiche-auteur.html.twig', [
            'auteur'    => $auteur,
            'reviews'  => $reviews
        ]);
    }




    //////////////////////////////////////////////////////////////////
    // ------------------------------------------------------ Addresse
    //////////////////////////////////////////////////////////////////


    /**
     * Paramètre
     * @Route("/params", name="params")
     */


    public function params(Request $request)
    {

        //////Ajout d'une adresse en BDD/////

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


        # Traitement des données POST
        $form->handleRequest($request);

        # Vérification des données du Formulaire
        if ($form->isSubmitted()) :

            # Récupération des données
            $address = $form->getData();

            dump($this);
            //die();

            # Insertion en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();


            # Redirection vers l'index
            return $this->render('index/index.html.twig', []);
        else :
            # Affichage de la Vue
            return $this->render('user/params.html.twig', [
                'form' => $form->createView(),
                'test'=> true,
            ]);
        endif;

        return $this->render('user/params.html.twig',[
            'form' => $form->createView(),
            'test'=> true,
        ]);
    }




    //////////////////////////////////////////////////////////////////
    // ---------------------------------------------------- Addresse Save
    //////////////////////////////////////////////////////////////////


    /**
     * Paramètre
     * @Route("/add-address", name="addressSave")
     */


    public function addressSave(Request $request)
    {

        //////Ajout d'une adresse en BDD/////

        // On crée une nouvelle address
        $address = new Address();


        # Vérification des données du Formulaire
        if (true) :

            dump($_POST);

            # Récupération des données
            $address->setStreet($_POST['form']['street']);
            $address->setZipCode($_POST['form']['zip_code']);
            $address->setCity($_POST['form']['city']);
            $address->setNumber($_POST['form']['number']);
            $address->setComment($_POST['form']['comment']);


            # Insertion en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();


            # Redirection vers l'index
            return $this->render('index/index.html.twig', []);
        else :
            # Affichage de la Vue
            return $this->render('user/params.html.twig', [
                'form' => $form->createView(),
                'test'=> true,
            ]);
        endif;

        return $this->render('user/params.html.twig',[
            'form' => $form->createView(),
            'test'=> true,
        ]);
    }





    //////////////////////////////////////////////////////////////////
    // ---------------------------------------------------- sendReview
    //////////////////////////////////////////////////////////////////




    /**
     * Page permettant d'envoyer un message
     * @Route("/sendReview/{user1}",
     *     name="sendReview",
     *     requirements={"user1" = "\d+"},
     *     methods={"POST"})
     * @param integer $user1
     * @return Response
     */

    public function sendReview($user1)
    {
        $review = new Review();

        # Récupération des variables de session
        $session = $this->get('session');

        // On récupère nos deux utilisateurs
        $emissaire = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($session->get('userId'));

        $destinataire = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user1);

        $review->setUser($emissaire);
        $review->setDestinataire($destinataire);


//        $formBuilder = $this->createFormBuilder($review);
//
//        //
//        $formBuilder
//            ->add('comments', TextType::class)
//            ->add('notes', IntegerType::class);
//
//        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard
//
//        // À partir du formBuilder, on génère le formulaire
//        $form = $formBuilder->getForm();

        dump($_POST);
        //die();



        # Récupération des données

        if(isset($_POST['notes'])) {
            $review->setNotes($_POST['notes']);
        }

        if(isset($_POST['comments'])) {
            $review->setComments($_POST['comments']);
        }

        // Fin de form

        # Insertion en BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($review);
        $em->flush();

        // Récupération de tous les reviews
        $reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findByUser($user1);

        // Validation

        # Redirection vers l'index
        return $this->redirectToRoute('index',[
//            'auteur'    => $emissaire,
//            'reviews'   => $reviews,
        ]);



    }




    //////////////////////////////////////////////////////////////////
    // ---------------------------------------------------- Send Mail
    //////////////////////////////////////////////////////////////////

    /**
     * @param \Swift_Mailer $mailer
     * @Route("/mail",name="sendMail")
     */
    public function sendMail(\Swift_Mailer $mailer){

        $message = (new \Swift_Message(''))
            ->setFrom('me@example.com')
            ->setTo('alallah@gmail.com')





            ->setBody(
                $this->renderView('emails/registration.html.twig',['name'=>'Ducon']
                ));


        $mailer->send($message);
        return $this->redirectToRoute('index');
    }




          //////////////////////////////////////////////////////////////////
        // -------------------------------------------------- ORDER
      //////////////////////////////////////////////////////////////////

    /**
     * Page permettant d'envoyer un message
     * @Route("/order/{id}",
     *     name="order",
     *     requirements={"id" = "\d+"},
     *     methods={"POST", "GET"})
     * @param integer id
     * @return Response
     */
    public function order($id)
    {

            // ----------------------------------------- RECETTE

        $recette = $this->getDoctrine()
            ->getRepository(Recipes::class)
            ->find($id);

        $cuisto = $recette->getCuisto();



        // ***************************************************

        // Récupération du status de la recette
        $status = $this->getDoctrine()
            ->getRepository(Status::class);

        $recette->setStatus($status->find(1));

//        if (($recette->getQuantity() - $_POST['quantity']) <= 0) :
//            $recette->setStatus($status->find(2));
//            $recette->setQuantity(0);
//        else :
//            $recette->setStatus($status->find(1));
//            $recette->setQuantity($recette->getQuantity() - $_POST['quantity']);
//        endif;

        // ***************************************************



            // ----------------------------------------- ADDRESS

        $address = $this->getDoctrine()
            ->getRepository(AddressHasUser::class)
            ->findByUser($cuisto);



            // ----------------------------------------- USER

        $session = $this->get('session');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($session->get('userId'));

            // ----------------------------------------- ORDER

        // Création d'une nouvelle order
        $order = new Orders();

        // Modification de order
        $order->setRecipes($recette);
        $order->setUser($user);
        $order->setQuantities($_POST['quantity']);
        $order->setStatus($recette->getStatus());



            // ----------------------------------------- BDD

        // SAUVEGARDE BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($recette);
        $em->persist($order);
        $em->flush();


            // ----------------------------------------- Redirection

        return $this->render('user/order.html.twig', [
            'order'     => $order,
            'address'   =>  $address
        ]);
    }





            //////////////////////////////////////////////////////////////////
          // ------------------------------------------------------ CHALLENGE
        //////////////////////////////////////////////////////////////////



    /**
     * Valider un challenge
     * @Route("/challenge/{id}",
     *     name="challenge",
     *     requirements={"id" = "\d+"},
     *     methods={"GET", "POST"})
     * @param integer $id
     * @return Response
     */

    public function challenge($id) {

        // ----------------------------------------- CHALLENGE
        $challenge = $this->getDoctrine()
            ->getRepository(Challenge::class)
            ->find($id);


        // ----------------------------------------- USER
        $session = $this->get('session');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($session->get('userId'));

        $user->setAvatar($challenge->getRecompense());


        // ----------------------------------------- BDD

        // SAUVEGARDE BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();


//        $reviews = $this->getDoctrine()
//            ->getRepository(Review::class)
//            ->findByAuteur($id);

        return $this->redirectToRoute('index', []);
    }


    //////////////////////////////////////////////////////////////////
    // ------------------------------------------- Histoire
    //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/histoire", name="histoire")
     */

    public function histoire() {

        # Affichage de la Vue
        return $this->render('commun/notrehistoire.html.twig', []);
    }


}