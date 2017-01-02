<?php

namespace AdminBundle\Form\Type;

use CoreBundle\Entity\PushSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PushType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PushSetting::class,
                'segments' => []
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $deliveryTimes = [
            'now' => 'now',
            'special' => 'special'
        ];

        $segments = $options['segments'];
        $builder
            ->add('title', TextType::class, [
                'label' => 'form.push.title',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.push.title'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'form.push.message',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'form.push.message'
                ]
            ])
            ->add('segment', ChoiceType::class, [
                'label' => 'form.push.segment',
                'required' => true,
                'choices' => array_flip($segments),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('deliveryTime', DateTimeType::class, [
                'label' => false,
                'format' => 'y-M-d H:i:s',
                'with_seconds' => true,
                'years' => range(date('Y'), date('Y') + 10)
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'label' => 'form.push.type',
                'choices' => $deliveryTimes,
                'choice_label' => function($value, $key, $index) {
                    return sprintf("form.push.type_choices.%s", $key);
                },
                'attr' => [
                    'class' => 'form-control push_delivery_time',
                    'children' => true
                ]
            ]);
    }
}
