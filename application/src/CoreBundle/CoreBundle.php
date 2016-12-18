<?php

namespace CoreBundle;

use CoreBundle\DependencyInjection\SNSDriverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SNSDriverCompilerPass());
    }
}
