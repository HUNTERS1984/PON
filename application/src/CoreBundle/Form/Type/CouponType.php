<?php

namespace CoreBundle\Form\Type;


use CoreBundle\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Coupon::class,
                'csrf_protection'   => false
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'type1' => 0,
                    'type2' => 1
                ]
            ])
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'required' => true,
            ])
            ->add('startDate', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('endDate', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('status', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'disabled' => 0,
                    'enabled' => 1
                ]
            ])
            ->add('imageUrl', TextType::class, [
                'required' => true,
            ])
            ->add('size', NumberType::class, [
                'required' => true,
            ]);
    }
}