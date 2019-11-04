<?php

namespace App\Form\Client;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichFileType::class, array(
                "label"=>"Client.form.image",
                'attr'=> array('placeholder'=>'insert an image here'),
                'required'      => false,
                'allow_delete'  => true,
                'download_uri' => false,
                'translation_domain' =>'Client'


            ))
            ->add('cin', TextType::Class, ['label'=>'Client.form.cin'])
            ->add('firstName', TextType::Class, ['label'=>'Client.form.firstName'])
            ->add('lastName', TextType::Class, ['label'=>'Client.form.lastName'])
            ->add('address', TextType::Class, ['label'=>'Client.form.address'])
            ->add('phoneNumber', NumberType::Class, ['label'=>'Client.form.phoneNumber'])
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
