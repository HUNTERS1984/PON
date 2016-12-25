<?php

namespace CoreBundle;

use CoreBundle\DependencyInjection\AnalyticsDriverCompilerPass;
use CoreBundle\DependencyInjection\EmailDriverCompilerPass;
use CoreBundle\DependencyInjection\NotificationDriverCompilerPass;
use CoreBundle\DependencyInjection\SNSDriverCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SNSDriverCompilerPass());
        $container->addCompilerPass(new EmailDriverCompilerPass());
        $container->addCompilerPass(new AnalyticsDriverCompilerPass());
        $container->addCompilerPass(new NotificationDriverCompilerPass());
    }
}
