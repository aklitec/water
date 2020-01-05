<?php


namespace App\Form\bill;

use App\Entity\Bill;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YearFinderType extends AbstractType
{

    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $repo = $this->em->getRepository(Bill::class);
        $yearsRepo = $repo->selectYear();


        $builder
            ->add('year', EntityType::Class, [
                'class' => Bill::class,
                'query_builder' => $yearsRepo,
                'choice_value' => function (Bill $entity = null) {
                    return $entity ? $entity->getConsumptionDate()->format('Y') : '';
                },
                'required' => false,
                'label' => 'bill.form.year',
                'mapped' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
            'translation_domain' => 'bill',
        ]);
    }
}