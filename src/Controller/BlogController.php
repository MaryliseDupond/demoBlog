<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
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
     * @Route("/blog/new", name="blog_create")
     */
    public function create(): Response
    {
        return $this->render('blog/create.html.twig');
    }


    // @Route("Route", name="RouteName")
    
    /**
     * Méthode permettant d'afficher le détail d'un article
     * Ici, Blog 4. 1er argument = chemin, 2ème argument = nom de la route
     * 
     * @Route("/blog/{id}", name="blog_show")
     */
    //!public function show($id): Response / SANS L'INJECTION DE DEPENDANCE
    //TODO Avec l'injection de dépendance:
    //?public function show(ArticleRepository $repoArticle, $id): Response
    public function show(Article $article): Response //! sera suivi de render
    {
        //TODO: l'id transmis dans l'URL est envoyé directement en argument de la fonction show(), ce qui nous permet d'avoir accès à l'id de l'article à sélectionnet en BDD au sein de la méthode show()
        //dump($id);// 4

        //TODO: importation de la classe ArticleRepository
        //? ligne non necessaire car j'ai utilisé l'injection de dépendance $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        //dump($repoArticle);//! j'ai bien un objet issu de la classe Repository

        //TODO: find() : méthode mise à disposition par Symfony issue de la classe ArticleRepository permettant de sélectionnet un élément de la BDD par son ID
        //TODO: $article : tableau ARRAY contenant toutes les données de l'article selectionné en BDD en fonction de l'ID transmit dans l'URL
        //? SELECT * FROM article WHERE id = 6 + FETCH

        //$article = $repoArticle->find($id);
        dump($article);//! On transmet au template les données de l'article sélectionné en BDD afin de les traiter avec le langage Twig dans le template

        // Affichera le template blog/show.html.twig
        return $this->render('blog/show.html.twig', [
            'articleBDD' => $article
        ]);
    }

    


}


