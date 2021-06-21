<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', null, [
                'label' => 'Nom du produit'
            ])
            ->add('price', null, [
                'label' => 'Prix'
            ])
            ->add('stock', null, [
                'label' => 'Quantité en stock'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('imageFileName', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required'=>false,
                'attr' => [
                    'placeholder' => 'Sélectionner une image',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '3096k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Envoyer un fichier image',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
