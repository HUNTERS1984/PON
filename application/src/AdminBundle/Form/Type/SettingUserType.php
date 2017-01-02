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
                'label' => 'form.setting_user.username',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.username',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'form.setting_user.new_password',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'パスワード',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'form.setting_user.confirm_password',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.confirm_password',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'form.setting_user.name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.setting_user.email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.email',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'form.setting_user.gender',
                'required' => true,
                'choices'  => [
                    'male' => 1,
                    'female' => 0,
                    'other' => 2
                ],
                'choice_label' => function($value, $key, $index) {
                    return sprintf("form.setting_user.gender_choices.%s", $key);
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('tel', TextType::class, [
                'label' => 'form.setting_user.tel',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.tel'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'form.setting_user.company',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.company'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'form.setting_user.address',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.setting_user.address'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'form.setting_user.avatar',
                'required' => false,
                'attr' => [
                    'class' => 'avatar_file'
                ],
            ]);
    }
}