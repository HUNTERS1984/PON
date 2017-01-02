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

class AppUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AppUser::class,
                'roles' => ['ROLE_CLIENT']
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
                'label' => 'form.app_user.username',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.username',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'form.app_user.name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.app_user.email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.email',
                    'autocomplete' => 'off',
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'form.app_user.gender',
                'required' => true,
                'choices'  => [
                    'male' => 1,
                    'female' => 0,
                    'other' => 2
                ],
                'choice_label' => function($value, $key, $index) {
                    return sprintf("form.app_user.gender_choices.%s", $key);
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('role', ChoiceType::class,[
                'required' => true,
                'label' => 'form.app_user.role',
                'choices'  => $options['roles'],
                'attr' => [
                    'class' => 'form-control form-role'
                ]
            ])
            ->add('store', StoreSearchType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-store'
                ],
                'store_label' => 'form.app_user.shop',
            ])
            ->add('tel', TextType::class, [
                'label' => 'form.app_user.tel',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.tel'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'form.app_user.company',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.company'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'form.app_user.address',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.address'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'form.app_user.avatar',
                'required' => false,
                'attr' => [
                    'class' => 'avatar_file'
                ],
            ]);
    }
}