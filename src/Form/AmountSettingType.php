<?php

namespace App\Form;

use App\Entity\AmountSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AmountSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amountRegister', IntegerType::class, [
                'attr' => [
                    'class' => 'col-4',
                    'min' => '0',
                    'max' => '100'
                ]
            ])
            ->add('reductionRegister', IntegerType::class, [
                'attr' => [
                    'class' => 'col-3',
                    'min' => '0',
                    'max' => '100',
                    'onmouseup' => "changePrice('1');",
                    'onkeyup' => "changePrice('1');"
                ]
            ])
            ->add('amountSubs', IntegerType::class, [
                'attr' => [
                    'class' => 'col-4',
                    'min' => '0',
                    'max' => '100'
                ]
            ])
            ->add('reductionSubs', IntegerType::class, [
                'attr' => [
                    'class' => 'col-3',
                    'min' => '0',
                    'max' => '100',
                    'onmouseup' => "changePrice('0');",
                    'onkeyup' => "changePrice('0');"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AmountSetting::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
