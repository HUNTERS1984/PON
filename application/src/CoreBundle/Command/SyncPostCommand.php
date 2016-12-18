<?php

namespace CoreBundle\Command;

use CoreBundle\Manager\PostManager;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncPostCommand extends ContainerAwareCommand
{
    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('pon:sync')
            ->setDescription('Sync Post SNS Data');
    }

    /**
     * @inheritdoc()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->syncPost($output);
    }

    /**
     * @param OutputInterface $output
     */
    public function syncPost(OutputInterface $output)
    {
        $result = $this->getPosts();
        $posts = $result['data'];
        $total = $result['total'];
        $date = new \DateTime();
        if($total == 0) {
            $output->writeln(sprintf("Time %s - <comment>`We don't have any posts to sync</comment>", $date->format('Y-m-d H:i:s')));
            return;
        }

        $output->writeln("");
        $output->writeln("Begin Processing...");
        $output->writeln("Starting Sync Post Data...");

        /**@var Producer $syncPostProducer */
        $syncPostProducer = $this->getContainer()->get('old_sound_rabbit_mq.sync_post_producer');
        $progress = new ProgressBar($output, $total);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        foreach($posts as $post) {
            $syncPostProducer->publish(serialize($post));
            $progress->advance();
        }
        $output->writeln("");
        $output->writeln("");
        $output->writeln(sprintf("Time %s - Finished Adding <comment>`Post Data`</comment> to a queue for processing", $date->format('Y-m-d H:i:s')));

    }

    public function getPosts()
    {
        /** @var PostManager $manager */
        $manager = $this->getContainer()->get('pon.manager.post');
        return $manager->listPost();
    }
}
