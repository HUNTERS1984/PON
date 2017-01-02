<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Form\Type\Common\SelectBoxType;
use CoreBundle\Entity\Category;
use CoreBundle\Manager\CategoryManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorySearchType extends AbstractType
{
    /**
     * @var CategoryManager $manager
     */
    protected $manager;

    /**
     * @param CategoryManager $manager
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
                'data_class' => Category::class,
                'show_category' => true,
                'category_label' => 'form.category_search.category'
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
                'label' => $options['category_label'],
                'min_length_to_search' => 3,
                'ajax-url' => 'admin_category_search',
                'placeholder' => 'form.category_search.place_holder',
                'choice_label' => function ($value) {
                    if ($value) {
                        /** @var Category $category */
                        $category = $this->manager->getCategory($value);
                        return $category ? "{$category->getName()}" : '';
                    }

                    return '';
                },
                'attr' => [
                    'data-toggle' => 'category',
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
