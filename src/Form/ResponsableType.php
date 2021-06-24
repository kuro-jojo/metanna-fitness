<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResponsableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                ],
                'label' => 'Nom',
                'label_attr' => ['class' => 'text-gray-900']
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                ],
                'label' => 'Prénom(s)',
                'label_attr' => ['class' => 'text-gray-900']
            ])
            ->add('telephone', TelType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder'=>'(00221) 77 XXX XX XX'
                ],
                'label' => 'Numéro de Téléphone',
                'label_attr' => ['class' => 'text-gray-900'],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Adresse Email',
                'label_attr' => ['class' => 'text-gray-900'],
                'help' => 'Un message de confirmation sera envoyé à cette adresse.'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => [
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => ['class' => 'text-gray-900'],
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'help' => 'Mot de passe par défaut du compte',
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'label_attr' => ['class' => 'text-gray-900'],
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => ' Au moins {{ limit }} charactères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('photoProfile', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Sélectionner une photo',
                    'onchange' => 'showPreview(event)',
                    'value' => 'photo profile',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '3096k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Envoyer un fichier image',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }
}
