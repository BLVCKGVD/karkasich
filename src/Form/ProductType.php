<?php

namespace App\Form;

use App\Entity\Product;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
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
            ->add('name', TextType::class,[
                'label' => $this->translator->trans('general.name')
            ])
            ->add('description',CKEditorType::class, [
                'label' => $this->translator->trans('general.description')
            ])
            ->add('images', FileType::class,[
                'multiple' => 'multiple',
                'required' => false,
                'mapped' => false,
                'constraints' => [new All(new Image([
                    'maxRatio' => 16/9,
                    'minRatio' => 16/9,
                    'maxRatioMessage' => 'Соотношение сторон должно быть 16:9',
                    'minRatioMessage' => 'Соотношение сторон должно быть 16:9']))],
                'label' => $this->translator->trans('general.images')
            ])
            ->add('cost', NumberType::class,[
                'label' => $this->translator->trans('general.cost')
            ])
            ->add('inMain', CheckboxType::class,[
                'required' => false,
                'label' => $this->translator->trans('general.inMain')
            ])
            ->add('isEnabled', CheckboxType::class,[
                'required' => false,
                'label' => $this->translator->trans('general.isEnabled')
            ])
            ->add('save', SubmitType::class,[
                'label' => $this->translator->trans('general.save')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
