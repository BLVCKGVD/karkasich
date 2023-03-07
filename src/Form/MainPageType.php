<?php

namespace App\Form;

use App\Entity\MainPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainPageType extends AbstractType
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
            ->add('mainText', TextareaType::class,
                [
                    'attr' => [
                        'rows' => 5
                    ]
                ])
            ->add('advantage1')
            ->add('advantage2')
            ->add('advantage3')
            ->add('images', FileType::class,
                [
                    'multiple' => 'multiple',
                    'required' => false,
                    'mapped' => false,
                ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('general.save')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainPage::class,
        ]);
    }
}
