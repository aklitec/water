<?php

namespace App\Form\WaterMeter;

use App\Entity\Client;
use App\Entity\WaterMeter;
use App\Form\Client\AddressType;
use App\Repository\ClientRepository;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class WaterMeterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('setupDate', DateType::class , [
                'label'=>'WaterMeters.index.setupDate',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'dd/mm/yyyy',
                ]
            ])
            ->add('active', CheckboxType::class , ['label'=>'WaterMeters.index.active', 'required' => false,])
            ->add('wmNumber', TextType::class , ['label'=>'WaterMeters.wmNumber'])
            ->add('address', AddressType::class )
            ->add('client', EntityType::class, array(
                'class' => Client::class,
                'query_builder' => function (ClientRepository $r) use ($options) {
                    //dd($r->select());
                        return $r->select();
                    },
                'placeholder' => 'Choisir...',

                'attr' => array(
                    'class' => 'select2',
                    'label' => 'Client',
                ),
            ));



    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WaterMeter::class,
            'translation_domain'=> 'WaterMeters'
        ]);
    }
}
