<?php

namespace App\Form\Client;

use App\Entity\Address;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                "label"=>"clients.form.image",
                'attr'=> array('placeholder'=>'insert an image here'),
                'required'      => false,
                'allow_delete'  => true,
                'download_uri' => false,
                'translation_domain' =>'clients'


            ))
            ->add('cin', TextType::Class, ['label'=>'clients.form.cin'])
            ->add('firstName', TextType::Class, ['label'=>'clients.form.firstName'])
            ->add('lastName', TextType::Class, ['label'=>'clients.form.lastName'])
            ->add('phoneNumber', NumberType::Class, ['label'=>'clients.form.phoneNumber'])
           // ->add('address', TextType::Class, ['label'=>'clients.form.address'])
             ->add('address', AddressType::class );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'translation_domain' => 'clients',
        ]);
    }
}
