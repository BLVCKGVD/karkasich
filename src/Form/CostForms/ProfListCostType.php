<?php

namespace App\Form\CostForms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfListCostType extends AbstractType
{
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('width',null,[
                'label' => $this->translator->trans('general.width')
            ])
            ->add('height',ChoiceType::class,[
                'label' => $this->translator->trans('general.height'),
                'choices' => [
                    "1.5 м" => 1.5,
                    "1.7 м" => 1.7,
                    "1.8 м" => 1.8,
                    "2.0 м" => 2.0,
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('general.save')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
