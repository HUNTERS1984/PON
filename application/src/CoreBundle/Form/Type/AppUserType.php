<?php

namespace CoreBundle\Form\Type;


use CoreBundle\Entity\AppUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'csrf_protection'   => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('temporaryHash', TextType::class, [
                'required' => true,
            ])
            ->add('androidPushKey', TextType::class, [
                'required' => true,
            ])
            ->add('applePushKey', TextType::class, [
                'required' => true
            ])
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'required' => true,
            ])
            ->add('gender', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Female' => 0,
                    'Male' => 1
                ]
            ])
            ->add('avatarUrl', TextType::class, [
                'required' => true,
            ])
            ->add('role', TextType::class, [
                'required' => true
            ]);
    }
}