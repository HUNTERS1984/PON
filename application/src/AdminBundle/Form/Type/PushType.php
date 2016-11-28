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
            ->add('segement', SegementType::class, [
                'label' => '配信先のセグメント',
                'segments' => $options['segements'],
                'required' => false
            ])
            ->add('deliveryTime', DateTimeType::class, [
                'label' => false,
                'format' => 'y-M-d H:i:s',
                'with_seconds' => true,
                'years' => range(date('Y'), date('Y') + 10),
                'attr' => [
                    'class' => 'select_day',
                ],
            ]);
    }
}
