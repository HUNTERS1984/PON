<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AdminBundle\Twig;

use ONGR\FilterManagerBundle\Pager\PagerService;
use Symfony\Component\Routing\RouterInterface;

/**
 * PagerExtension extends Twig with pagination capabilities.
 */
class PagerExtension extends \Twig_Extension
{
    /**
     * Twig extension name.
     */
    const NAME = 'pon_pager';

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pon_paginate_path', [$this, 'path'], ['is_safe' => []]),
        ];
    }

    /**
     * Generates url to certain page.
     *
     * @param string $route
     * @param string $page
     * @param array  $parameters
     *
     * @return string
     */
    public function path($route, $page, array $parameters = [])
    {
        $fieldName = 'page_index';

        if (isset($parameters['page'])) {
            $fieldName = $parameters['page'];
            unset($parameters['page']);
        }

        $parameters[$fieldName] = $page;

        return $this->router->generate($route, $parameters);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
