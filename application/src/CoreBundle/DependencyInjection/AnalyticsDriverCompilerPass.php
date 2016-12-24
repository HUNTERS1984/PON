<?php

namespace CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AnalyticsDriverCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('pon.analytics.client')) {
            return;
        }

        $definition = $container->findDefinition('pon.analytics.client');

        $factories = $container->findTaggedServiceIds('pon_analytics');

        foreach ($factories as $id => $factory) {
            $definition->addMethodCall(
                'setDriver',
                array(new Reference($id))
            );
        }
    }
}
