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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{
    private DateService $dateService;
    private TranslatorInterface $translator;

    /**
     * @param DateService $dateService
     * @param TranslatorInterface $translator
     */
    public function __construct(DateService $dateService, TranslatorInterface $translator)
    {
        $this->dateService = $dateService;
        $this->translator = $translator;
    }


    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => $this->translator->trans('general.firstName'),
            ])
            ->add('phone', TelType::class, [
                'label' => $this->translator->trans('general.phone'),
                'attr' => [
                    'pattern' => '^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => $this->translator->trans('general.email'),
                'help' => 'необязяательно'
            ])
            ->add('text', TextareaType::class, [
                'required' => false,
                'label' => $this->translator->trans('general.text'),
                'help' => 'необязяательно'
            ])
            ->add('actionText', HiddenType::class)
            ->add('status', HiddenType::class, [
                'empty_data' => 'new'
            ])
            ->add('createdAt', HiddenType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-warning text-white'],
                'label' => $this->translator->trans('general.feedback')]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
