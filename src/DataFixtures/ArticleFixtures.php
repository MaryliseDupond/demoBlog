<?php
//TODO: on a crée les fixtures via une ligne de commande via le composer et doctrine
namespace App\DataFixtures;

use DateTime;
use App\Entity\Tag;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //TODO: On importe la librairie Faker pour les fixtures, cela nous permet de créer des fausses articles, catégories, commentaires plus évolués avec par exemple des faux noms, faux prénoms, date aléatoires etc...
        $faker = \Faker\Factory::create('fr_FR');

        //TODO: creation de 3 catégories
        for ($cat = 1; $cat <= 3; $cat++) {
            $category = new Category;

            $category->setTitre($faker->word)
                ->setDescription($faker->paragraph());

            $manager->persist($category);

            //TODO: creation de 2 tags (Corporis et Voluptatem)
            /**
            * for ($tag = 1; $cat <= 2; $tag++) {
            * $tag = new Tag;
            * $tag->setName($faker->word);
            * $manager->persist($tag);
            */


            //TODO: création de 4 à 10 article par catégorie
            for ($art = 1; $art <= mt_rand(4, 10); $art++) {
                #? $faker->paragraphs(5) retourne un array, setContenu attend une chaine de caractères en argument
                //?: join (alias implode) permet d'extraire chaque paragraphe faker afin de les rassembler en une chaine de caractères avec un séparateur (<p></p>)
                $contenu = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $article = new Article;

                $article->setTitre($faker->sentence())
                    ->setContenu($contenu)
                    ->setImage($faker->imageUrl(600, 600))
                    ->setDate($faker->dateTimeBetween('-6months'))
                    ->setCategory($category);
                $manager->persist($article);

                //TODO: création de 4 à 10 commentaires pour chaque article
                for ($cmt = 1; $cmt <= mt_rand(4, 10); $cmt++) {
                    //TODO: TRAITEMENT DES PARAGRAPHES DE COMMENTAIRES
                    $contenu = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';
                    $comment = new Comment;

                    //TODO: TRAITEMENT DES DATES
                    $now = new DateTime();
                    $interval = $now->diff($article->getDate()); //? retourne un timestamp (temps en secondes) entre la date de création des article et aujourd'hui

                    $days = $interval->days; //? retourne le nombre de jours entre la date de création des articles et aujourd'hui

                    $minimum = "-$days days"; //! -100 days | le but est d'avoir des dates de commentaires entre la date de creéation des articles et aujourd'hui

                    $comment->setAuteur($faker->name)
                        ->setContenuDuCommentaire($contenu)
                        ->setDate($faker->dateTimeBetween($minimum)) //! dateTimeBetween (-10 days)
                        ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
