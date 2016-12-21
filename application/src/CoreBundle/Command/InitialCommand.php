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

class InitialCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('initial:data')
            ->setDescription('Dummy Data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Clear Upload Files...");
        $file = new Filesystem();
        $uploadPath = $this->getContainer()->getParameter('uploads_path');
        if ($file->exists($uploadPath)) {
            $file->remove($uploadPath);
        }
        $file->mkdir($uploadPath);

        $output->writeln("Finished Clearing...");

        $output->writeln("");
        $output->writeln("Begin Processing...");
        $output->writeln("Starting Dump Client...");
        $this->dummyClient($output);
        $output->writeln("");
        $output->writeln("Finished Dump Client...");

        $output->writeln("");
        $output->writeln("Starting Dump AppUser...");
        $this->dummyAppUser($output);
        $output->writeln("");
        $output->writeln("Finished Dump AppUser...");

        $output->writeln("");
        $output->writeln("Progress Finished.");
    }

    public function dummyClient(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 1);
        $progress->start();
        $output->writeln("");

        /** @var $client Client */
        $client = $this->getContainer()->get('pon.dummy.client')->generate($output);
        $output->write([
            sprintf("client_id: %s", $client->getPublicId()),
            sprintf("client_secret: %s", $client->getSecret())
        ], true);
        $output->writeln("");
        $progress->advance();

        $progress->finish();
    }

    public function dummyAppUser(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 1);
        $progress->setRedrawFrequency(1);
        $progress->start();

        /** @var AppUserDummy */
        for ($i = 0; $i < 1; $i++) {
            $this->getContainer()->get('pon.dummy.app_user')->generate($output, $i);
            $progress->advance();
        }

        $progress->finish();
    }
}