<?php


namespace App\Form\Client;


use App\Entity\Address;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('streetAddress', TextType::Class, ['label' => 'clients.form.streetAddress'])
            ->add('houseNumber', NumberType::Class, ['label' => 'clients.form.houseNumber'])
            ->add('city', TextType::Class, ['label' => 'clients.form.city'])
            ->add('district', TextType::Class, ['label' => 'clients.form.district'])
            ->add('zipCode', NumberType::Class, ['label' => 'clients.form.zipCode']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'translation_domain' => 'clients',
        ]);
    }
}
