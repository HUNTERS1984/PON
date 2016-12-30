<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Category::class
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
                'label' => '名',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '名'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'カテゴリ画像を変更する',
                'required' => false,
                'attr' => [
                    'class' => 'avatar_file'
                ],
            ]);
    }
}