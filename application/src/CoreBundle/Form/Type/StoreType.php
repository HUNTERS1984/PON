<?php

namespace CoreBundle\Form\Type;


use CoreBundle\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Store::class,
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
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('latitude', TextType::class, [
                'required' => true,
            ])
            ->add('longtitude', TextType::class, [
                'required' => true,
            ])
            ->add('storeType', StoreTypeType::class, [
                'required' => true
            ]);
    }
}