<?php

namespace CoreBundle\Form\Type;


use CoreBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Post::class,
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
            ->add('socialUserId', IntegerType::class, [
                'required' => true
            ])
            ->add('socialMediaId', TextType::class, [
                'required' => true
            ])
            ->add('caption', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'required' => true,
            ])
            ->add('createdTime', DateTimeType::class, [
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
            ->add('socialUserName', TextType::class, [
                'required' => true,
            ])
            ->add('socialAvatar', TextType::class, [
                'required' => true,
            ])
            ->add('imageUrl', TextType::class, [
                'required' => true,
            ])
            ->add('url', TextType::class, [
                'required' => true,
            ]);
    }
}