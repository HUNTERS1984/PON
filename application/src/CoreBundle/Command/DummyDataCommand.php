<?php

namespace CoreBundle\Command;

use CoreBundle\DummyData\CategoryDummy;
use CoreBundle\DummyData\ClientDummy;
use CoreBundle\DummyData\LevelDummy;
use CoreBundle\DummyData\LocationDummy;
use CoreBundle\DummyData\SalaryRangeDummy;
use CoreBundle\DummyData\UserDetailDummy;
use CoreBundle\DummyData\UserSkillDummy;
use CoreBundle\Entity\User;
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
        $this->dummyUser($output);
        $output->writeln("\n");

        $output->writeln("Progress Finished.");
    }

    public function dummyClient(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
        $progress->start();
        /** @var ClientDummy */
        $this->getContainer()->get('pon.dummy.client')->generate();
        $output->writeln("\n");
        $progress->advance();

        $progress->finish();
    }

    public function dummyUser(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
        $progress->start();
        $command = $this->getApplication()->find('fos:user:create');
        $arguments = array(
            'username' => 'admin',
            'email' => 'admin@pon.dev',
            'password' => 'admin'
        );
        $input = new ArrayInput($arguments);
        $returnCode = $command->run($input, $output);
        $progress->advance();
        if($returnCode == 0) {
            $output->writeln("Create user is successfully. \n");
        }else{
            $output->writeln("Create user is failed. \n");
        }

        $progress->finish();
    }
}