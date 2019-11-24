<?php

namespace App\Form;

use App\Entity\Consumption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'dd/mm/yyyy',
                ]
            ])
            ->add('previousRecord', NumberType::class,['disabled'=>true])
            ->add('currentRecord')
            ->add('consumption',NumberType::class,[
                'disabled'=>true
            ])
            ->add('costPerMeterCube',NumberType::class,[ 'attr'=> array('readonly'=>true)])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consumption::class,
        ]);
    }
}
