<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Form\Type\Common\SelectBoxType;
use CoreBundle\Entity\NewsCategory;
use CoreBundle\Manager\NewsCategoryManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsCategorySearchType extends AbstractType
{
    /**
     * @var NewsCategoryManager $manager
     */
    protected $manager;

    /**
     * @param NewsCategoryManager $manager
     *
     * @return $this
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => NewsCategory::class,
                'show_category' => true,
                'news_category_label' => 'form.news_category_search.news_category'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', SelectBoxType::class, [
                'required' => true,
                'label' => $options['news_category_label'],
                'min_length_to_search' => 3,
                'ajax-url' => 'admin_news_category_search',
                'placeholder' => 'form.news_category_search.place_holder',
                'choice_label' => function ($value) {
                    if ($value) {
                        /** @var NewsCategory $newsCategory */
                        $newsCategory = $this->manager->getNewsCategory($value);
                        return $newsCategory ? "{$newsCategory->getName()}" : '';
                    }

                    return '';
                },
                'attr' => [
                    'data-toggle' => 'news_category',
                    'class' => 'form-control select2',
                ]
            ]);

        $builder->get('id')->resetViewTransformers();
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }
}
