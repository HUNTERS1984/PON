<?php

namespace CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pon:refresh:data')
            ->setDescription('Refresh Data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheManager = $this->getContainer()->get('fos_http_cache.cache_manager');
        $cacheManager->flush();

    }
}