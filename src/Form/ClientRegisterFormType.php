<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class ClientRegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                null,
                [
                    'label' => 'Nom du client',
                    'label_attr' =>
                    [
                        'class' => 'form-label',
                    ],
                    'attr' =>
                    [
                        'placeholder' => 'Doe'
                    ]
                ]
            )
            ->add(
                'prenom',
                null,
                [
                    'label' => 'Prénom(s) du client',
                    'label_attr' =>
                    [
                        'class' => 'form-label',
                    ],
                    'attr' =>
                    [
                        'placeholder' => 'John'
                    ]
                ]
            )
            ->add('telephone', TelType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => '(00221) 77 XXX XX XX'
                ],
                'label' => 'Téléphone',
                'label_attr' => ['class' => 'text-gray-900'],
            ])
            ->add('dateNaissance', DateType::class, [
                'widget'=>'single_text',
                'label' => 'Date de naissance',

            ])
            ->add('photo');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
