<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\AppUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AppUser::class
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class, [
                'label' => 'ユーザー名',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ユーザー名',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'パスワード',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'パスワード',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'パスワード(確認)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'パスワード(確認)',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'フルネーム',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'フルネーム'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Eメール',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Eメール',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => '性別',
                'required' => true,
                'choices'  => [
                    '男性' => 1,
                    '女性' => 0,
                    'その他' => 2
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('tel', TextType::class, [
                'label' => '電話',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '電話'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => '会社',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '会社'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => '住所',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '住所'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'アバター画像を変更する',
                'required' => false
            ]);
    }
}