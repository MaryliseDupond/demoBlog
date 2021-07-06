<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function blog(): Response
    {
        //! traitement requête de sélection BDD des articles
        //TODO: $repoArticles est un objet issu de de la classe ArticleRepository
        $repoArticles = $this->getDoctrine()->getRepository(Article::class);
        dump($repoArticles);

        $articles = $repoArticles->findAll();
        dump($articles);

        return $this->render('blog/blog.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * Méthode permettant d'afficher le rendu de la page d'accueil du blog Symfony. Symfony lit le commentaire route pour trouver la route. Il faut écrire le code tel quel (l. 11 à 13)
     * @Route("/" , name = "home")
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Blog dédié à la musique, viendez voir, ça déchire 🐱‍👤!!!',
            'age' => 25
        ]);
    }


    // @Route("Route", name="RouteName")
    
    /**
     * Méthode permettant d'afficher le détail d'un article
     * Ici, Blog 12 est bidon car je n'ai pas encore de BDD. 1er argument = chemin, 2ème argument = nom de la route
     * 
     * @Route("/blog/12", name="blog_show")
     */
    public function show(): Response
    {
        return $this->render('blog/show.html.twig');
    }
}


