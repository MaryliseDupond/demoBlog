<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * Méthode permettant d'afficher l'ensemble des articles du blog
     * 
     * @Route("/blog", name="blog")
     */
    
    //! Injection de dépendance: methode blog dépend de ArticleRepository - On indique entre les parenthèses l'objet et la classe dont l'objet est issu
     public function blog(ArticleRepository $repoArticles): Response
    {
        //TODO: pour sélectionner des données dans une table SQL en BDD, nous devons importer la classe Repository qui correspond à la table SQL.
        //TODO Une table repository permet uniquement de formuler et d'exécuter des requêtes de SQL de selection (SELECT)
        //TODO Cette classe contient des méthodes mis à disposition par Symfony pour formuler et executer des requetes SQL en BDD
        //TODO getRepository(): méthode permettant d'importer la classe Repository d'une entité
        
        //! traitement requête de sélection BDD des articles
        //! $repoArticles est un objet issu de de la classe ArticleRepository
        // $repoArticles = $this->getDoctrine()->getRepository(Article::class);
        //?VOIR AUTRE ECRITURE EN L17: on indique directement dans la methode blog(). C'est l'injection de dépendance

        //! dump () : outil de debug propre à Symfony
        dump($repoArticles); // dd() = Dump and Die: Elle permet d'afficher du texte à l'écran et de terminer l'exécution du programme

        //! articles aux pluriel car on aura tous les articles
        //? findAll() : SELECT * FROM article + FECHALL
        //TODO: $articles = tableau ARRAY multidimensional contenant l'ensemble des articles stockés dans la BDD
        $articles = $repoArticles->findAll();
        dump($articles);

        //! retourne un tableau array 
        return $this->render('blog/blog.html.twig', [
            'articlesBDD' => $articles // On transmet au template les articles que nous avons sélectionnés en BDD afin de les traiter et de les afficher avec le langage TWIG
        ]);
    }

    /**
     * Méthode permettant d'afficher le rendu de la page d'accueil du blog Symfony. Symfony lit le commentaire route pour trouver la route. Il faut écrire le code tel quel (l. 11 à 13)
     * @Route("/" , name = "home")
     */
    public function home(): Response
    {
        //! affichera sous forme de tableau le template issu du blog/home.html.twig
        return $this->render('blog/home.html.twig', [
            'title' => 'Blog dédié à la musique, viendez voir, ça déchire 🐱‍👤!!!',
            'age' => 25
        ]);
    }

    /**
     * Méthode permettant de créer un nouvel article et de modifier un article existant
     * 
     * @Route("/blog/new_old", name="blog_create_old")
     */
    public function createOld(Request $request, EntityManagerInterface $manager): Response //! Injection de dépendance Request $request
    
    {
        dump($request);
        //TODO: La classe Request permet de stocker et d'avoir accès aux données véhiculées par les superglobales ($_POST, $_GET, $_COOKIE, $_FILES etc...)

        //? la propriété request permet de stocker et d'accéder aux données saisies dans le formulaire, càd aux données de la superglobale $_POST
        //! Si les données sont supérieures à 0, donc si nous avons bien saisi des données dans le formulaire, alors on entre dans la condition IF

        if($request->request->count()>0)
        {
            //? Si nous voulons insérer des données dans la table SQL Article, nous devons instancier et remplir un objet issu de son entité correspondante (classe Article)
            $article = new Article;

            //TODO: On renseigne tous les setteurs de l'objet avec les données saisies dans le formulaire
            //? request->request->get('titre'): permet d'atteindre la valeur du titre saisi dans le champ titre du formulaire
            $article->setTitre($request->request->get('titre'))
                    ->setContenu($request->request->get('contenu'))
                    ->setImage($request->request->get('image'))
                    ->setDate(new \DateTime());

            dump($article);

            //! Pour manipuler les lignes de la BDD (INSERT, UPDATE, DELETE), nous avons besoin d'un mamager (EntityManagerInterface)
            //? persist() : méthode issue de l'interface EntityManagerInterface permettant de préparer et garder en mémmoire la requete d'insertion
            //TODO equivaut à $data = $bdd->prepare("INSERT INTO article VALUES ('$article->getTitre()', '$article->getContenu()')")
            $manager->persist($article);

            //? flush() : méthode issue de l'interface EntityManagerInterface permettant veritablement d'executer le requete d'insertion en BDD
            //TODO $data->excecute()
            $manager->flush();
            dump($article);

            //? Après l'insertion de l'article en BDD, nous redirigeons l'internaute vers l'affichage du détail de l'article donc une autre route via la méthode redirectToRoute
            //TODO: cette methode attend 2 arguments:
            //TODO 1) la Route
            //TODO 1) le paramètre a transmettre dans la route, dans notre cas l'ID de l'article
            return $this->redirectToRoute('blog_show', [
                'id' =>$article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig');//! Template html.twig à créer
    }
    /**
     * Méthode permettant d'afficher le détail d'un article ou de le modifier
     * 
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    //TODO: on a demandé à l'ORM de créer un table via php bin/console make:form
    //TODO: on a deamndé de créer la table par rapport à l'entité Article et nous l'avons appelé ArticleType
    
    public function create(Article $article = null, Request $request, EntityManagerInterface $manager): Response
    {
        //! Si la variable $article N'EST PAS (null), si elle ne contient aucun article de la BDD, cela veut dire nous avons envoyé la route '/blog/new', c'est une insertion, on entre dans le IF et on crée une nouvelle instance de l'entité Article, création d'un nouvel article
        //? Si la variable $article contient un article de la BDD, cela veut dire que nous avons envoyé la route '/blog/id/edit', c'est une modification d'article, on n'entre pas dans le IF, $article ne sera pas null, il contient un article de la BDD, l'article à modifier
        if(!$article)
        { 
           $article = new Article;
        }

        //! En renseigant les setteurs de l'entité on s'apperçoit que les valeurs sont envoyées directement dans les attributs 'value'du formulaire, cela est dû au fait que l'entité $article est relié au formulaire
        //$article->setTitre('Faux titre')
         //       ->setContenu ('Faux contenu');

        dump($request);

        //TODO createForm() permet ici de créer un formulaire d'ajout d'article en fonction de la classe ArticleType (que l'on trouve dans form et que nous avions créé)
        //TODO En 2ème argument de createForm(), nous transmettons l'objet entité $article afin de préciser que le formulaire a pour but de remplir l'objet $article, on relie l'entité au formulaire
        $formArticle = $this->createForm(ArticleType::class, $article);

        //? handleRequest() permet ici dans notre cas, de récupérer toutes les données saisies dans le formulaire et de les transmettre aux bons setteurs de l'entité $article 
        //! handleRequest() renseigne chaque setteur de l'entité $article avec les données saisies dans le formulaire
        $formArticle->handleRequest($request);
        dump($article);

        //? Si le formulaire a bien été validé && que toutes les données saisies sont bien transmises à la bonne entité, alors on entre dans la condition IF
        if($formArticle->isSubmitted() && $formArticle->isValid())
        {
            //!On renseigne le  setteur de la date car nous n'avons pas de champs 'date' dans le formulaire
            //TODO: Si l'article ne possède pas d'ID, alors on entre dans la condition IF et on execute le setteur de la date, on entre dans le IF que dans le cas de la création d'un nouvel article

            if(!$article->getId())
            {
                $article->setDate(new \DateTime());
            }
            

            //! Pour manipuler les lignes de la BDD (INSERT, UPDATE, DELETE), nous avons besoin d'un mamager (EntityManagerInterface)
            //? persist() : méthode issue de l'interface EntityManagerInterface permettant de préparer et garder en mémmoire la requête d'insertion
            //TODO equivaut à $data = $bdd->prepare("INSERT INTO article VALUES ('$article->getTitre()', '$article->getContenu()')")
            $manager->persist($article);
            

            //? flush() : méthode issue de l'interface EntityManagerInterface permettant veritablement d'executer le requete d'insertion en BDD
            //TODO $data->excecute()
            $manager->flush();

            //? Nous redirigeons l'internaute après l'insertion de l'article en BDD vers une autre route via la méthode redirectToRoute
            //TODO: cette methode attend 2 arguments:
            //TODO 1) la Route
            //TODO 1) le paramètre a transmettre dans la route, dans notre cas l'ID de l'article
            return $this->redirectToRoute('blog_show', [
                'id' =>$article->getId()
            ]);

        }

        return $this->render('blog/create2.html.twig',[
            'formArticle' => $formArticle->createView(), //?on transmet le formulaire au template afin de pouvoir l'afficher avec Twig
            //TODO: createView() va retourner un petit objet qui représente l'affichage du formulaire, on le récupère dans le template create2.html.twig
            'editMode' => $article->getID()//! On transmet l'id de l'article au template
        ]);

    
    }
    


    // @Route("Route", name="RouteName")
    
    /**
     * Méthode permettant d'afficher le détail d'un article
     * Ici, Blog/4. 1er argument = chemin, 2ème argument = nom de la route
     * 
     * @Route("/blog/{id}", name="blog_show")
     */
    //?public function show($id): Response / SANS L'INJECTION DE DEPENDANCE
    //TODO Avec l'injection de dépendance:
    //! public function show(ArticleRepository $repoArticle, $id): Response
    public function show(Article $article, Request $request): Response //! sera suivi de render
    {
        //TODO: l'id transmis dans l'URL est envoyé directement en argument de la fonction show(), ce qui nous permet d'avoir accès à l'id de l'article à sélectionnet en BDD au sein de la méthode show()
        //dump($id);// 4
        dump($request);// Les données du commentaire vont ben dans le request


        //TODO: importation de la classe ArticleRepository
        //? ligne non necessaire car j'ai utilisé l'injection de dépendance $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        //dump($repoArticle);//! j'ai bien un objet issu de la classe Repository

        //TODO: find() : méthode mise à disposition par Symfony issue de la classe ArticleRepository permettant de sélectionnet un élément de la BDD par son ID
        //TODO: $article : tableau ARRAY contenant toutes les données de l'article selectionné en BDD en fonction de l'ID transmit dans l'URL
        //? SELECT * FROM article WHERE id = 6 + FETCH

        //$article = $repoArticle->find($id);
        dump($article);//! On transmet au template les données de l'article sélectionné en BDD afin de les traiter avec le langage Twig dans le template

        //? Traitement commentaire article (formulaire + insertion)
        $comment = new Comment;

        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);
        dump($comment);
        

        // Affichera le template blog/show.html.twig
        return $this->render('blog/show.html.twig', [
            'articleBDD' => $article, //! On transmet au template les données de l'articles sélectionné en BDD afin de les traiter avec le langage TWIG dans le template
            'formComment' => $formComment->createView()
        ]);
    }

    


}


