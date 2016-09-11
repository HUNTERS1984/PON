<?php

namespace CoreBundle\Form\Type;


use CoreBundle\Entity\User;
use CoreBundle\Form\Model\UserModel;
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

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
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
            ->add('userName', TextType::class, [
                'required' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('fullName', TextType::class, [
                'required' => true,
            ])
            ->add('sex', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Female' => 0,
                    'Male' => 1
                ]
            ])
            ->add('birthday', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('locale', TextType::class, [
                'required' => true
            ])
            ->add('temporaryHash', TextType::class, [
                'required' => true
            ])
            ->add('rememberToken', TextType::class, [
                'required' => true
            ])
            ->add('company', TextType::class, [
                'required' => true
            ])
            ->add('address', TextType::class, [
                'required' => true
            ])
            ->add('tel', TextType::class, [
                'required' => true
            ])
            ->add('imageUser', TextType::class, [
                'required' => true
            ]);
    }
}