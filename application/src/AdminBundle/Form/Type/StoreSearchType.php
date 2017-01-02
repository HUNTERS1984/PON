<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Form\Type\Common\SelectBoxType;
use CoreBundle\Entity\Store;
use CoreBundle\Manager\StoreManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreSearchType extends AbstractType
{
    /**
     * @var StoreManager $manager
     */
    protected $manager;

    /**
     * @param StoreManager $manager
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
                'data_class' => Store::class,
                'show_category' => true,
                'store_label' => 'form.store_search.shop'
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
                'label' => $options['store_label'],
                'min_length_to_search' => 3,
                'ajax-url' => 'admin_store_search',
                'placeholder' => 'form.store_search.place_holder',
                'choice_label' => function ($value) {
                    if ($value) {
                        /** @var Store $store */
                        $store = $this->manager->getStore($value);
                        return $store ? "{$store->getTitle()}" : '';
                    }

                    return '';
                },
                'attr' => [
                    'data-toggle' => 'store',
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
