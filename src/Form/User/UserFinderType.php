<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFinderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'users.form.fullName',
                ],
            ])
            ->add('username', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'users.form.username',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'users.form.email',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'user',
        ]);
    }
}
