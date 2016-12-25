<?php

namespace CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NotificationDriverCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('pon.notification.client')) {
            return;
        }

        $definition = $container->findDefinition('pon.notification.client');

        $factories = $container->findTaggedServiceIds('pon_notification');

        foreach ($factories as $id => $factory) {
            $definition->addMethodCall(
                'setDriver',
                array(new Reference($id))
            );
        }
    }
}
