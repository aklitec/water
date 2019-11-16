<?php

namespace App\Form\Client;

use App\Entity\Client;
use App\Form\WaterMeter\WaterMeterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFinderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'clients.form.fullName',
                ],
            ])
            ->add('cin', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'clients.form.cin',
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'clients.form.phoneNumber',
                ],
            ])
            ->add('code', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'clients.form.code',
                ],
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'translation_domain' => 'clients',
        ]);
    }
}
