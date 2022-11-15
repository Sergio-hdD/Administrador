<?php

namespace App\Form;

use App\Entity\Matter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['attr' => ['placeholder' => 'Name'], 'label' => 'Name', 'required' => true])
            ->add('yearCarrer', ChoiceType::class, ['label' => 'Seleccione el año',
                'choices'  => [
                    'Seleccione el año' => 0,
                    'Primero' => 'Primero',
                    'Segundo' => 'Segundo',
                    'Tercero' => 'Tercero',
                    'Cuarto' => 'Cuarto',
                    'Quinto' => 'Quinto'
                ], 
            ])
            ->add('isFirstFourMonth', null, ['label' => 'Es del primer cuatrimestre'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matter::class,
        ]);
    }
}
