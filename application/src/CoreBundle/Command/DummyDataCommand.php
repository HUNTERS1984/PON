<?php

namespace CoreBundle\Command;

use CoreBundle\DummyData\AppUserDummy;
use CoreBundle\DummyData\ClientDummy;
use CoreBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DummyDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dummy:data')
            ->setDescription('Dummy Data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dummyClient($output);
        $output->writeln("\n");
        $this->dummyAppUser($output);
        $output->writeln("\n");

        $output->writeln("Progress Finished.");
    }

    public function dummyClient(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
        $progress->start();

        /** @var $client Client */
        $client = $this->getContainer()->get('pon.dummy.client')->generate();
        $output->write([
            sprintf("client_id: %s", $client->getPublicId()),
            sprintf("client_secret: %s", $client->getSecret())
        ], true);
        $output->writeln("\n");
        $progress->advance();

        $progress->finish();
    }

    public function dummyAppUser(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
        $progress->start();

        /** @var AppUserDummy */
        for ($i = 0; $i < 20; $i++) {
            $this->getContainer()->get('pon.dummy.app_user')->generate($i);
            $this->getContainer()->get('pon.dummy.category')->generate($i);
            $this->getContainer()->get('pon.dummy.user')->generate($i);
        }

        for ($i = 0; $i < 190; $i++) {
            $this->getContainer()->get('pon.dummy.store')->generate($i);
            $this->getContainer()->get('pon.dummy.coupon')->generate($i);
        }

        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 6; $j++) {
                $this->getContainer()->get('pon.dummy.use_list')->generate($i, $j);
            }
        }

        $progress->finish();
    }
}