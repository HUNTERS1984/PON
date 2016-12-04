<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\Coupon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('status', ChoiceType::class, [
                'label' => '状態',
                'required' => true,
                'choices'  => [
                    'アクティブ' => 1,
                    '非アクティブ' => 0
                ],
                'attr' => [
                    'class' => 'form-control'
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
            ->add('needLogin', ChoiceType::class,[
                'label' => 'ログインする必要がある',
                'choices'  => [
                    'はい' => 1,
                    'いいえ' => 0
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
            ])
            ->add('size', IntegerType::class, [
                'label' => '量',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '量'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => '説明文',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => '説明文'
                ],
            ])
            ->add('expiredTime', DateType::class, [
                'label' => '期限',
                'format' => 'y-M-d',
                'years' => range(date('Y'), date('Y') + 10),
                'attr' => [
                    'class' => 'select_day',
                ],
            ])
            ->add('couponPhotos', CollectionType::class, [
                'label' => 'クーポンの写真',
                'required' => false,
                'entry_type' => CouponPhotoType::class,
                'by_reference' => false,
                'show_when_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('couponUserPhotos', CollectionType::class, [
                'label' => 'ユーザーの写真',
                'required' => false,
                'entry_type' => CouponUserPhotoType::class,
                'by_reference' => false,
                'show_when_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])->add('imageFile', FileType::class, [
                'label' => 'アカウントイメージの変更',
                'required' => false
            ])
            ->add('store', StoreSearchType::class, [
                'label' => false,
                'store_label' => 'ショップ',
            ]);
    }
}