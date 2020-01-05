<?php


namespace App\Form\bill;


use App\Entity\Address;
use App\Entity\Consumption;
use App\Entity\WaterMeter;
use App\Repository\AddressRepository;
use App\Repository\ConsumptionRepository;
use App\Repository\WaterMeterRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReleaseBillType extends AbstractType
{

    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months=[
           'Janvier'=>'01','février'=>'02','mars'=>'03','avril'=>'04', 'mai'=>'05', 'juin'=>'06',
            'juillet'=>'07','août'=>'08','septembre'=>'09', 'octobre'=>'10','novembre' =>'11','décembre'=>'12'
        ];




        $repo = $this->em->getRepository(Consumption::class);
        $yearsRepo = $repo->selectYear();





        $builder
            ->add('year', EntityType::Class, [
                'class'=>Consumption::class,
                'query_builder'=>$yearsRepo,
                'choice_value' => function (Consumption $entity = null) {
                    return $entity ? $entity->getDate()->format('Y'): '';
                },
                'required' => true,
                'label' => 'bill.form.year',
                'mapped' => false,
            ])
            ->add('month', ChoiceType::Class, [
                'required' => true,
                'label' => 'bill.form.month',
                'mapped' => false,
                'choices'=>$months,

            ])
            ->add('city', EntityType::Class, [
                'class' => WaterMeter::class,
                'required' => true,
                'query_builder'=>function(WaterMeterRepository $r){
                    return $r->select();
                },
                'choice_value' => function (WaterMeter $entity = null) {
                    return $entity ? $entity->getAddress()->getCity() : '';
                },
                'label' => 'bill.form.month',
                'expanded' => false,
                'multiple' => false,

            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>Address::class,
            'translation_domain' => 'bill',

        ]);
    }
}