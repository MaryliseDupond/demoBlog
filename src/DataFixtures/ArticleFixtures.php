<?php
//TODO: on a crée les fixtures via une ligne de commande via le composer et doctrine
namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager; //! Classe de Symfony qui permet entre autre de manipuler les données (c'est l'ORM)
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    //! Générée par Symfony et qui sert à charger les éléments dans la BDD
    public function load(ObjectManager $manager) //! Objet issu de la classe manager
    {
        //! La boucle FOR tourne 11 fois car nous voulons créer 11 articles
        for($i = 1; $i <= 11; $i++)
        {
            // Pour pouvoir insérer des données dans la table SQL article, nous devons instancier son entité correspondante (Article), Symfony se sert l'objet entité $article pour injecter les valeurs dans les requetes SQL
            //!Création de l'entité (entité = reflet de la table) article
            $article = new Article;

            //! Appel à tous les SETTERS afin d'injecter du contenu dans la BDD, enrenseignant les titres, les contenus, les images et dates des faux articles  stockés en BDD
            $article->setTitre("Titre de l'article $i")
                    ->setContenu("<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sagittis neque diam, eu lacinia metus ultricies et. Pellentesque lobortis velit id commodo vestibulum. Nulla ut rutrum dui. Nulla quis malesuada neque. Praesent et nulla a eros finibus hendrerit et non erat. Proin varius mauris et lorem pharetra elementum. Pellentesque faucibus enim nec tempor lobortis. Duis laoreet elementum mauris, nec porta ex scelerisque ullamcorper. Proin sodales a urna nec condimentum. Nulla purus augue, gravida et lacus convallis, scelerisque tincidunt justo. Donec dictum mauris urna, id tempus dui pharetra at. Nunc eget vehicula quam.</p>")
                    ->setImage("https://picsum.photos/600/600")
                    ->setDate(new \DateTime());

            //! Appel du Manager. Un manager (ObjectManager) en Symfony est un classe permettant, entre autre, de manipuler les lignes de la BDD (INSERT, UPDATE, DELETE)
            $manager->persist($article);//! persist() va récupérer les données et les garde en mémoire les données
            //? persit(): méthode issue de la classe ObjectManager
            //TODO: cette ligne équivaut à:
            //TODO: $data = $bdd->prepare(INSERT INTO article VALUES ('getTitre()', getContenu()' etc)")
        }
        //! flush() : méthode issue de la classe ObjectManager permettant véritablement d'executer les requetes d'insertions en BDD
        $manager->flush();//TODO Balance le contenu dans la BDD équivaut à execute()

        //TODO: on demande à doctrine de charger les fixtures via la ligne de commande php bin/console doctrine:fixture:load et le tout sera inséré dans la nouvelle table
    }
}
