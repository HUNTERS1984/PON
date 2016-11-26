<?php

namespace AdminBundle\Form\Type\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutocompleteType extends AbstractType
{
    const INFINITY = 'Infinity';

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'source' => [],
                'limit' => static::INFINITY,
                'min_length_to_search' => 2,
                'display_path' => '',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['ajax_url'] = is_string($options['source']) ? $options['source'] : '';

        $view->vars['attr'] = array_merge([
            'data-toggle' => 'autocomplete',
            'data-source' => json_encode($options['source']),
            'data-limit' => $options['limit'],
            'data-min-length' => $options['min_length_to_search'],
            'data-display' => $options['display_path'],
        ], $options['attr']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
}
