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
                'segements' => []
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $deliveryTimes = [
            '今すぐ送信してください' => 'now',
            '時間を指定して配信' => 'special'
        ];

        $segements = array_merge([null=> 'お店から1キロ以内のユーザー'],$options['segements']);

        $builder
            ->add('title', TextType::class, [
                'label' => 'タイトル',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'タイトル'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'メッセージ',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'メッセージ'
                ]
            ])
            ->add('json', TextareaType::class, [
                'label' => 'JSON',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'JSON'
                ]
            ])
            ->add('segement', ChoiceType::class, [
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
            ])
            ->add('deliveryTime', DateTimeType::class, [
                'label' => false,
                'format' => 'y-M-d H:i:s',
                'with_seconds' => true,
                'years' => range(date('Y'), date('Y') + 10)
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'label' => '配信時間指定',
                'choices' => $deliveryTimes,
                'attr' => [
                    'class' => 'form-control push_delivery_time',
                    'children' => true
                ]
            ]);
    }
}
