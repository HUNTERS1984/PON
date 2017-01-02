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
                'label' => 'form.news.title',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.news.title'
                ]
            ])
            ->add('introduction', TextareaType::class, [
                'label' => 'form.news.introduction',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'form.news.introduction'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'form.news.description',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'form.news.description'
                ]
            ])
            ->add('newsCategory', NewsCategorySearchType::class, [
                'label' => false,
            ]);
    }
}