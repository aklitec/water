<?php

namespace App\Form\Client;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
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
                    'placeholder' => 'Client.form.fullName',
                ],
            ])
            ->add('cin', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Client.form.cin',
                ],
            ])
            ->add('phoneNumber', EmailType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Client.form.phoneNumber',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'translation_domain' => 'Client',
        ]);
    }
}
