<?php

namespace App\Form\WaterMeter;

use App\Entity\WaterMeter;
use App\Form\Client\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WaterMeterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('setupDate', DateType::class , ['label'=>'WaterMeters.index.setupDate'])
            ->add('active', CheckboxType::class , ['label'=>'WaterMeters.index.active', 'required' => false,])
            ->add('address', AddressType::class );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WaterMeter::class,
            'translation_domain'=> 'WaterMeters'
        ]);
    }
}
