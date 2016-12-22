<?php

namespace CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EmailDriverCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('pon.mailer.client')) {
            return;
        }

        $definition = $container->findDefinition('pon.mailer.client');

        $factories = $container->findTaggedServiceIds('pon_email');

        foreach ($factories as $id => $factory) {
            $definition->addMethodCall(
                'setDriver',
                array(new Reference($id))
            );
        }
    }
}
