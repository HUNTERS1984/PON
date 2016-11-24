<?php

namespace AdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'entry_title' => '',
                'add_label' => 'form.collection.add',
                'prototype' => true,
                'prototype_name' => uniqid(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true,
                'show_when_empty' => true,
                'width' => 'col-sm-4'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['add_label'] = $options['add_label'];
        $view->vars['prototype_name'] = $options['prototype_name'];
        $view->vars['show_when_empty'] = $options['show_when_empty'];
        $view->vars['entry_title'] = $options['entry_title'];
        $view->vars['width'] = $options['width'];
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CollectionType::class;
    }
}
