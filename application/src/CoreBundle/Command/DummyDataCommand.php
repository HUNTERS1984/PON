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
use Symfony\Component\Filesystem\Filesystem;

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
        $output->writeln("Clear Upload Files...");
        $file = new Filesystem();
        $uploadPath = $this->getContainer()->getParameter('uploads_path');
        if($file->exists($uploadPath)) {
            $file->remove($uploadPath);
        }
        $file->mkdir($uploadPath);

        $output->writeln("\n");
        $output->writeln("Finished Clearing...");

        $output->writeln("Begin Processing...");
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
        for($i = 0; $i< 5; $i++) {
            $this->getContainer()->get('pon.dummy.category')->generate($i);
        }
        for ($i = 0; $i < 5; $i++) {
            $this->getContainer()->get('pon.dummy.app_user')->generate($i);
            $this->getContainer()->get('pon.dummy.app_user')->generateStoreUsers($i);
        }

        for($i=0; $i< 10; $i ++) {
            $this->getContainer()->get('pon.dummy.store')->generate();
        }

        for ($i = 0; $i < 50; $i++) {
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