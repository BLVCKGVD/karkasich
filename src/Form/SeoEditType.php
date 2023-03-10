<?php

namespace App\Form;

use App\Entity\Seo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SeoEditType extends AbstractType
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
            ->add('id', null, [
                'mapped' => false,
                'attr' => [
                    'class' => 'd-none'
                ],
                'label_attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('path', null, [
                'label' => $this->translator->trans('general.path')
            ])
            ->add('description', null, [
                'label' => $this->translator->trans('general.description')
            ])
            ->add('robots', null, [
                'label' => $this->translator->trans('general.robots')
            ])
            ->add('title', null, [
                'label' => $this->translator->trans('general.title')
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('general.save')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seo::class,
        ]);
    }
}
