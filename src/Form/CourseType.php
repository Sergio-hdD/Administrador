<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('untilChangeNote', DateType::class, [
                'required'=>false,
                'widget' => 'single_text',
                // 'format' => 'yyyy-MM-dd',
                'by_reference' => true,
            ])
            ->add('dateStart', DateType::class, [
                'required'=>false,
                'widget' => 'single_text',
                // 'format' => 'yyyy-MM-dd',
                'by_reference' => true,
            ])
            ->add('dateEnd', DateType::class, [
                'required'=>false,
                'widget' => 'single_text',
                // 'format' => 'yyyy-MM-dd',
                'by_reference' => true,
            ])
            ->add('dayCourse', ChoiceType::class, ['label' => 'Seleccione el día',
                'choices'  => [
                    'Seleccione el día' => 0,
                    'Lunes' => 'Lunes',
                    'Martes' => 'Martes',
                    'Miércoles' => 'Miércoles',
                    'Jueves' => 'Jueves',
                    'Viernes' => 'Viernes',
                    'Sábado' => 'Sábado'
                ], 
            ])
            
            ->add('turn', ChoiceType::class, ['label' => 'Seleccione el turno',
                'choices'  => [
                    'Seleccione el turno' => 0,
                    'Mañana' => 'Mañana',
                    'Tarde' => 'Tarde',
                    'Noche' => 'Noche',
                ], 
            ])
            ->add('matter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
