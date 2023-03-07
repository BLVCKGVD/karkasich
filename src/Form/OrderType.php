<?php

namespace App\Form;

use App\Entity\Order;
use App\Service\DateService;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    private DateService $dateService;

    /**
     * @param DateService $dateService
     */
    public function __construct(DateService $dateService)
    {
        $this->dateService = $dateService;
    }


    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('phone', TelType::class)
            ->add('email', EmailType::class)
            ->add('text', TextareaType::class)
            ->add('actionText', HiddenType::class)
            ->add('status', HiddenType::class, [
                'empty_data' => 'new'
            ])
            ->add('createdAt', HiddenType::class,[
                'required'=>false
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
