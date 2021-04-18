<?php

namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ClientRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('dateOfRegistration', DateType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Date de naissance',
            //     'format' => 'd MMMM yyyy',
            //     'html5'=>false,
            //     'attr' => [
            //         'disabled' => true,
            //         'class'=>'text-center'
            //     ]
            // ])
            // ->add('deadline', DateType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Date de naissance',
            //     'attr' => [
            //         'disabled' => true,
            //         'class'=>'text-center'
            //     ]
            // ])
            // ->add('amountOfRegistration', null,
            // [
            //     'label_attr' =>
            //     [
            //         'class' => 'form-label',
            //     ],
            //     'attr' => [
            //         'disabled'=>true,
            //         'class'=>'text-center'
            //     ]
            // ])
            ->add('registeredClient', ClientFormType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
            'attr'=>[
                'novalidate'=>'novalidate'
            ]
        ]);
    }
}
