<?php

namespace CoreBundle\Command;

use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\SocialProfileManager;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrappingDataCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('pon:scrapping')
            ->setDescription('Scrapping SNS Data');
    }

    /**
     * @inheritdoc()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->scrappingData($output);
    }

    /**
     * @param OutputInterface $output
     */
    public function scrappingData(OutputInterface $output)
    {
        $result = $this->getSocialProfiles();
        $socialProfiles = $result['data'];
        $total = $result['total'];

        if($total == 0) {
            $output->writeln("<comment>`We don't have any users to scrapping data`</comment>");
            return;
        }

        $output->writeln("");
        $output->writeln("Begin Processing...");
        $output->writeln("Starting Scrapping Data...");

        /**@var Producer $scrappingProducer */
        $scrappingProducer = $this->getContainer()->get('old_sound_rabbit_mq.scrapping_producer');
        $progress = new ProgressBar($output, $total);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");

        foreach($socialProfiles as $socialProfile) {
            $scrappingProducer->publish(serialize($socialProfile));
            $progress->advance();
        }
        $output->writeln("");
        $output->writeln("");
        $output->writeln("Finished Adding <comment>`Scrapping SNS Data`</comment> to a queue for processing");

    }

    public function getSocialProfiles()
    {
        /** @var SocialProfileManager $manager */
        $manager = $this->getContainer()->get('pon.manager.social_profile');
        return $manager->getSocialProfileOfUsers();
    }
}
