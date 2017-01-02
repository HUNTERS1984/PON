<?php

namespace AdminBundle\Form\Type\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SelectBoxType extends AbstractType
{
    const INFINITY = 0;

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'placeholder' => 'form.select_box.select',
                'tags' => false,
                'token_separators' => [','],
                'min_length_to_search' => static::INFINITY,
                'max_length_to_search' => static::INFINITY,
                'ajax-url' => '',
                'ajax-pager-name' => '',
                'ajax-cache' => true,
                'ajax-delay' => 250,
                'ajax-value-path' => 'id',
                'ajax-display-path' => 'name',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = array_merge([
            'data-toggle' => 'select',
            'data-width' => '100%',
            'data-placeholder' => $options['placeholder'],
            'data-allow-clear' => var_export(!$options['required'], true),
            'data-tags' => var_export($options['tags'], true),
            'data-token-separators' => json_encode($options['token_separators']),
            'data-minimum-input-length' => $options['min_length_to_search'],
            'data-maximum-input-length' => $options['max_length_to_search'],
        ], $options['attr']);

        if ($options['ajax-url']) {
            $attr = array_merge([
                'data-ajax--url' => $options['ajax-url'],
                'data-ajax--cache' => var_export($options['ajax-cache'], true),
                'data-ajax--delay' => $options['ajax-delay'],
                'data-ajax-value-path' => $options['ajax-value-path'],
                'data-ajax-display-path' => $options['ajax-display-path'],
                'data-ajax-pager-name' => $options['ajax-pager-name'],
            ], $attr);

            if ($form->getData()) {
                $data = $form->getData();

                $view->vars['choices'][] = new ChoiceView(
                    $data,
                    $this->normalizeOption($data, $options['choice_value']),
                    $this->normalizeOption($data, $options['choice_label'])
                );
                $view->vars['value'] = $data;
            }
        }

        $view->vars['attr'] = $attr;
        $view->vars['placeholder'] = $attr['data-placeholder'];
        $view->vars['ajax_url'] = array_key_exists('data-ajax--url', $attr) ? $attr['data-ajax--url'] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @param mixed $data
     * @param mixed $option
     *
     * @return mixed
     */
    private function normalizeOption($data, $option)
    {
        switch (true) {
            case empty($option):
                return $data;

            case is_string($option):
                $processor = new PropertyAccessor();

                return $processor->getValue($data, $option);

            case is_callable($option):
                return $option($data, 0, 0);

            default:
                return $data;
        }
    }
}
