<?php

namespace App\Form;

use App\Entity\CasualClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CasualClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom',
                'help'=> 'Si disponible'
            ])
            ->add('prenom',TextType::class,[
                'label'=>'Prénom(s)',
                'help'=> 'Si disponible'
            ])
            ->add('amount',IntegerType::class,[
                'label' => 'Montant payé',
                'attr' => [
                    'min' => 0,
                ],
                'constraints' => [
                    new NotBlank ([
                        
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CasualClient::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
