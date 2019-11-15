<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('fullName', TextType::class)
            ->add('avatarFile', VichFileType::class, array(
                'required'      => false,
                'allow_delete'  => true,
                'download_uri' => false,
            ))
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'users.role.user' => 'ROLE_USER',
                    'users.role.admin' => 'ROLE_ADMIN',
                    'users.role.super' => 'ROLE_SUPER_ADMIN',
                ),
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' => 'The password fields must match.',
                'first_options' => array('label' => 'user.labels.password'),
                'second_options' => array('label' => 'user.labels.password-repeat'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'user'
        ]);
    }
}
