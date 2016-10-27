<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'data_class' => Coupon::class
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
                'label' => 'クーポンタイトル',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'クーポンタイトル'
                ]
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'クーポンタイプ',
                'choices'  => [
                    'ユーザー投稿型' => null,
                    'SNS' => 1,
                    'Store' => 2
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('hashTag', TextareaType::class, [
                'label' => 'ハッシュタグ',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => 'ハッシュタグ'
                ],
            ]);
    }
}