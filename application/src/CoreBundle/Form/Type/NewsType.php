<?php

namespace CoreBundle\Form\Type;


use CoreBundle\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => News::class,
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
            ->add('title', TextType::class, [
                'required' => true
            ])
            ->add('message', TextType::class, [
                'required' => true
            ])
            ->add('json', TextType::class, [
                'required' => true,
            ])
            ->add('type', IntegerType::class, [
                'required' => true,
            ])
            ->add('time', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('date', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ]);
    }
}