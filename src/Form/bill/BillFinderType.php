<?php


namespace App\Form\bill;


use App\Entity\Bill;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillFinderType extends AbstractType
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
        $months=[
            'Janvier'=>'01','février'=>'02','mars'=>'03','avril'=>'04', 'mai'=>'05', 'juin'=>'06',
            'juillet'=>'07','août'=>'08','septembre'=>'09', 'octobre'=>'10','novembre' =>'11','décembre'=>'12'
        ];

        $builder
            ->add('fullName', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'bill.form.fullName',
                ],
            ])
            ->add('cin', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'bill.form.cin',
                ],
            ])
            ->add('code', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'bill.form.code',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'bill.form.status',
                'choices'=>[
                    'Not Paid'=>'0',
                    'Paid'=>'1'
                ]
            ])
            ->add('month', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'bill.form.month',
                'choices'=>$months,
                'mapped' => false,
            ])
            ->add('year', EntityType::Class, [
                'class' => Bill::class,
                'query_builder' => $yearsRepo,
                'placeholder'=> 'bill.form.year',
                'choice_value' => function (Bill $entity = null) {
                    return $entity ? $entity->getConsumptionDate()->format('Y') : '';
                },
                'required' => false,
                'label' => 'bill.form.year',
                'mapped' => false,
             ]);
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
            'translation_domain' => 'bill',
        ]);
    }
}