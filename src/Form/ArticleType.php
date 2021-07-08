<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'attr' =>[
                    'placeholder' => "Saisir le titre de l'article"
            ]
            ])

            ->add('contenu', TextareaType::class, [
                'label' => "Détail de l'article",
                'attr' =>[
                    'placeholder' => "Contenu de l'article",
                    'rows' => 8
                ]
            ])
            ->add('image',TextType::class, [
                'attr' =>[
                    'placeholder' => "Saisir l'URL de l'image"
            ]
            ])
            //->add('date') Je n'en ai pas besoin car je ne vais pas demander à l'internaute de rentrer la date et l'heure à laquelle il a crée l'article)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
