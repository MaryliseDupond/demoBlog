<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //! la fonction add permet de créer les champs du fomulaire, cela remplace les setters
            ->add('titre', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le titre de l'article"

                ]
            ])
            ->add('category', EntityType::class, [

                # On définit le champ qui permet d'associer une catégorie à l'article dans le formulaire
                # Ce champ provient d'une autre entité : Category
                'class' => Category::class, //TODO on précise de quelle entité provient ce champ

                'choice_label' => 'titre' //TODO le contenu de la liste déroulante sera le titre des catégories

            ])
            ->add('tags', EntityType::class, [

                # permet d'avoir un champ SELECT qui va lister toutes les données d'une entité
                # Ce champ provient d'une autre entité : Tag
                'class' => Tag::class, //TODO on précise de quelle entité provient ce champ

                'choice_label' => 'name', //TODO le contenu de la liste déroulante des tag
                'multiple' => true

            ])

            ->add('contenu', TextareaType::class, [
                'label' => "Détail de l'article",
                'required' => false,
                'attr' => [
                    'placeholder' => "Contenu de l'article",
                    'rows' => 8
                ]
            ])

            ->add('image', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir l'URL de l'image"
                ]
            ]);

        #->add('date') Je n'en ai pas besoin car je ne vais pas demander à l'internaute de rentrer la date et l'heure à laquelle il a crée l'article). Le système l'a généré car ce champs figure dans l'Article.php

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
