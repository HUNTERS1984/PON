<?php

namespace AdminBundle\Form\Type;

use CoreBundle\Entity\Segement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SegementType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Segement::class,
                'segments' => []
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $segements = array_merge([null=> 'お店から1キロ以内のユーザー'],$options['segments']);
        $builder
            ->add('title', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'choices' => $segements,
                'choice_label' => function ($value) {
                    if(!is_object($value)) {
                        return $value;
                    }
                    return $value ? $value->getTitle() : null;
                },
                'choice_value' => function ($value) {
                    if(!is_object($value)) {
                        return null;
                    }
                    return $value ? $value->getId() : null;
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }
}
