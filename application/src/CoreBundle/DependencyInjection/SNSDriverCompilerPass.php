<?php

namespace CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SNSDriverCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('pon.sns.client')) {
            return;
        }

        $definition = $container->findDefinition('pon.sns.client');

        $factories = $container->findTaggedServiceIds('sns');

        foreach ($factories as $id => $factory) {
            $definition->addMethodCall(
                'addDriver',
                array(new Reference($id))
            );
        }
    }
}
