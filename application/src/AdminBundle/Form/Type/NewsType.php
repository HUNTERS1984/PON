<?php

namespace AdminBundle\Form\Type;


use CoreBundle\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'data_class' => News::class
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
                    'placeholder' => '名'
                ]
            ])
            ->add('introduction', TextareaType::class, [
                'label' => '導入',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => '導入'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => '説明',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => '説明'
                ]
            ])
            ->add('newsCategory', NewsCategorySearchType::class, [
                'label' => false,
                'news_category_label' => 'ニュースカテゴリ',
            ]);
    }
}