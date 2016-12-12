<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingStoreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Store::class
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
            ->add('operationStartTime', DateTimeType::class, [
                'label' => '操作開始時刻',
                'format' => 'y-M-d H:i',
                'required' => true,
            ])
            ->add('operationEndTime', DateTimeType::class, [
                'label' => '操作終了時刻',
                'format' => 'y-M-d H:i',
                'required' => true,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'アカウントイメージの変更',
                'required' => false
            ])
            ->add('tel', TextType::class, [
                'label' => '電話',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '電話'
                ],
                'required' => true
            ])
            ->add('latitude', TextType::class, [
                'label' => 'latitude',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'latitude'
                ],
                'required' => true
            ])
            ->add('longitude', TextType::class, [
                'label' => 'longitude',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'longitude'
                ],
                'required' => true
            ])
            ->add('address', TextType::class, [
                'label' => '住所',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '住所'
                ],
                'required' => true
            ])
            ->add('aveBill', NumberType::class, [
                'label' => '平均予算',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '平均予算'
                ],
                'required' => true
            ])
            ->add('closeDate', TextareaType::class, [
                'label' => 'アクセス',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => 'アクセス'
                ],
            ])
            ->add('helpText', TextareaType::class, [
                'label' => '説明',
                'attr' => [
                    'rows' => '5',
                    'class' => 'form-control',
                    'placeholder' => '説明'
                ],
            ])
            ->add('category', CategorySearchType::class, [
                'label' => false,
                'store_label' => 'ショップ',
            ])
            ->add('storePhotos', CollectionType::class, [
                'label' => 'ショップ写真',
                'required' => false,
                'entry_type' => StorePhotoType::class,
                'by_reference' => false,
                'show_when_empty' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }
}