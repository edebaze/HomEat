<?php

namespace App\Controller;

use App\Controller\Helper;
use App\Entity\CategoriesRecipes;
use App\Entity\Challenge;
use App\Entity\Level;
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
    /*

    HELPER contient les fonctions utilisées afin
    de rendre le code plus lisible et aéré

    */

    use Helper;



        //////////////////////////////////////////////////////////////////
      // ------------------------------------------------------ SIGNIN
    //////////////////////////////////////////////////////////////////


    /**
     * Formulaire pour ajouter un utilisateur
     * @Route("/signin", name="signin")
     */

    public function signin() {

        # Vérification des données du Formulaire
        if (isset($_POST['form'])) :

            $form = $_POST['form'];

            $auteur = $this->newAuteur($form);

            $this->save($auteur);
            $this->initiateSession($auteur);

            $this->newUserChallenge($auteur);
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

            $repository = $this->getDoctrine()
                ->getRepository(User::class);

            # Récupération des données

                # On regarde si l'utilisateur est dans la BDD
                $error = $repository->loginUser($_POST['mail'], $_POST['pass']);

                if(!empty($error[0])) :
                    $user = $this->getThisUser();

                    $recettes = $this->getDoctrine()
                        ->getRepository(Recipes::class)
                        ->findAll();

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
    // ----------------------------------------------------- EDITOR
    //////////////////////////////////////////////////////////////////


    /**
     * Formulaire de connexion
     * @Route("/editor", name="editor")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function editor(Request $request) {

        // Récupération des variables
        $auteur = $this->getThisUser();
        $categorie = $this->getDoctrine()
            ->getRepository(CategoriesRecipes::class)
            ->find(1);
        $status = $this->getDoctrine()
            ->getRepository(Status::class)
            ->find(2);

        $form = $this->formEditor()
            ->handleRequest($request);

        # Vérification des données du Formulaire
        if ($form->isSubmitted()) :

            $recette = $form->getData();

            $recette->setCategories($categorie);
            $recette->setCuisto($auteur);
            $recette->setStatus($status);

            ($recette->getImage() == null) ? $recette->setImage('images/recettes/default.jpg') : $this->saveImage($recette);

            $this->save($recette);

            # Redirection vers l'index
            return $this->redirectToRoute('index');

        endif;


        # Affichage du Formulaire dans la Vue
        return $this->render('commun/editor.html.twig', [
            'form'      => $form->createView(),
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

        // ----------------------------------------- Auteur
        $auteur = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);


        // ----------------------------------------- Orders & Ventes
        $repository = $this->getDoctrine()
            ->getRepository(Orders::class);

        $orders = $repository->findByUser($auteur);

        $recipes = $this->getDoctrine()
            ->getRepository(Recipes::class)
            ->findByUser($auteur);

        $ventes = $repository->findByRecipes($recipes);

        // ----------------------------------------- Messages
        $reviews = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findByUser($auteur);



                        # Redirection

        return $this->render('auteur/affiche-auteur.html.twig', [
            'auteur'    => $auteur,
            'orders'    => $orders,
            'ventes'    => $ventes,
            'reviews'   => $reviews,
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

        $form = $this->formAddress();

        # Traitement des données POST
        $form->handleRequest($request);

        # Vérification des données du Formulaire
        if ($form->isSubmitted()) :

            # Récupération des données
            $address = $form->getData();

            $this->save($address);

            # Redirection vers l'index
            return $this->redirectToRoute('index');
        endif;

        return $this->render('user/params.html.twig',[
            'form' => $form->createView()
        ]);
    }




    //////////////////////////////////////////////////////////////////
    // ---------------------------------------------------- Addresse Save
    //////////////////////////////////////////////////////////////////


    /**
     * Paramètre
     * @Route("/add-address", name="addressSave")
     */


    public function addressSave()
    {
        # Vérification des données du Formulaire
        if (isset($_POST)) :

            $post = $_POST['form'];
            $this->setAddress($post);

            # Redirection vers l'index
            return $this->render('index/index.html.twig', []);

        endif;

        return $this->render('user/params.html.twig');
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

        $emissaire = $this->getThisUser();

        $destinataire = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user1);

        $review->setUser($emissaire);
        $review->setDestinataire($destinataire);

        if(isset($_POST['notes'])) {
            $review->setNotes($_POST['notes']);
        }

        if(isset($_POST['comments'])) {
            $review->setComments($_POST['comments']);
        }


        # CHALLENGE ref 1

        if($review->getNotes() == 5) {
            $challenges = $this->getDoctrine()
                ->getRepository(Challenge::class)
                ->findByRef(1);

            $this->setUserChallenge($destinataire, $challenges);
        }

        $this->save($review);

        # Redirection vers l'index
        return $this->redirectToRoute('index');

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

            // ----------------------------------------- RECEPIES

        $recette = $this->getDoctrine()
            ->getRepository(Recipes::class)
            ->find($id);


            // ----------------------------------------- ADDRESS

        $address = $this->getDoctrine()
            ->getRepository(AddressHasUser::class)
            ->findByUser($recette->getCuisto());


            // ----------------------------------------- ORDER

        $order = $this->setOrder($recette);


            // ----------------------------------------- BDD

        $this->save($order);


                            # Redirection

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
        $user = $this->getThisUser();
        $user->setAvatar($challenge->getImage());


        // ----------------------------------------- BDD

        $this->save($user);


        return $this->redirectToRoute('index');
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




    //////////////////////////////////////////////////////////////////
    // ------------------------------------------- cancelOrder
    //////////////////////////////////////////////////////////////////


    /**
     * Supprimer une commande
     * @Route("/cancelorder/{id}",
     *     name="cancelorder",
     *     requirements={"id" = "\d+"},
     *     methods={"GET", "POST"})
     * @param integer $id
     * @return Response
     */

    public function cancelorder($id) {

        $order = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->find($id);

        $recipes =  $order->getRecipes();

        $order->setCancel(true);
        $recipes->setQuantity($recipes->getQuantity() + $order->getQuantities());

        if($recipes->getStatus()->getId() == 2) {
            $status = $this->getDoctrine()
                ->getRepository(Status::class)
                ->find(1);

            $recipes->setStatus($status);
        }

        $this->save($order);
        $this->save($recipes);

        # Affichage de la Vue
        return $this->redirect('http://localhost:8000/auteur/'.$this->getThisUser()->getId());
    }



    //////////////////////////////////////////////////////////////////
    // ---------------------------------------------------- geoloc
    //////////////////////////////////////////////////////////////////


    /**
     * Paramètre
     * @Route("/geoloc", name="geoloc")
     */


    public function geoloc()
    {

        include(__DIR__ . "/geoloc/geoipcity.inc");

        $gi = geoip_open(realpath(__DIR__ . "/geoloc/GeoLiteCity.dat"),GEOIP_STANDARD);

        $record = geoip_record_by_addr($gi,$_POST['ip']);

        $la = $record->latitude;
        $lo = $record->longitude;

        $coordonnees = array('lat' => $la, 'lng' => $lo);

        setcookie('lat', $la);
        setcookie('lng', $lo);
        setcookie('ip', $_POST['ip']);

        echo json_encode($coordonnees);


        geoip_close($gi);

        return $this->redirectToRoute('index');
    }



}