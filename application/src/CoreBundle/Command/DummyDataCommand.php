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
        $output->writeln("Starting Dump Category...");
        $this->dummyCategory($output);
        $output->writeln("");
        $output->writeln("Finished Dump Category...");

        $output->writeln("");
        $output->writeln("Starting Dump NewsCategory...");
        $this->dummyNewsCategory($output);
        $output->writeln("");
        $output->writeln("Finished Dump NewsCategory...");

        $output->writeln("");
        $output->writeln("Starting Dump Store...");
        $this->dummyStore($output);
        $output->writeln("");
        $output->writeln("Finished Dump Store...");

        $output->writeln("");
        $output->writeln("Starting Dump AppUser...");
        $this->dummyAppUser($output);
        $output->writeln("");
        $output->writeln("Finished Dump AppUser...");

        $output->writeln("");
        $output->writeln("Starting Dump Segement...");
        $this->dummySegement($output);
        $output->writeln("");
        $output->writeln("Finished Dump Segement...");

        $output->writeln("");
        $output->writeln("Starting Dump PushSetting...");
        $this->dummyPushSetting($output);
        $output->writeln("");
        $output->writeln("Finished Dump PushSetting...");

        $output->writeln("");
        $output->writeln("Starting Dump MessageDelivery...");
        $this->dummyMessageDelivery($output);
        $output->writeln("");
        $output->writeln("Finished Dump MessageDelivery...");

        $output->writeln("");
        $output->writeln("Starting Dump Coupon...");
        $this->dummyCoupon($output);
        $output->writeln("");
        $output->writeln("Finished Dump Coupon...");

        $output->writeln("");
        $output->writeln("Starting Dump News...");
        $this->dummyNews($output);
        $output->writeln("");
        $output->writeln("Finished Dump News...");

        $output->writeln("");
        $output->writeln("Starting Dump UseList...");
        $this->dummyUseList($output);
        $output->writeln("");
        $output->writeln("Finished Dump UseList...");

        $output->writeln("");
        $output->writeln("Progress Finished.");
    }

    public function dummyClient(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
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
        $progress = new ProgressBar($output, 150);
        $progress->setRedrawFrequency(1);
        $progress->start();

        /** @var AppUserDummy */
        for ($i = 0; $i < 50; $i++) {
            $this->getContainer()->get('pon.dummy.app_user')->generate($output, $i);
            $progress->advance();
        }

        for ($i = 0; $i < 50; $i++) {
            $this->getContainer()->get('pon.dummy.app_user')->generateStoreUsers($output, $i);
            $progress->advance();
        }

        for ($i = 0; $i < 50; $i++) {
            $this->getContainer()->get('pon.dummy.app_user')->generateAppUsers($output, $i);
            $progress->advance();
        }


        $progress->finish();
    }

    public function dummyCategory(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 5);
        $progress->setRedrawFrequency(1);
        $progress->start();

        for ($i = 0; $i < 5; $i++) {
            $this->getContainer()->get('pon.dummy.category')->generate($output);
            $progress->advance();
        }
        $progress->finish();
    }

    public function dummyNewsCategory(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 5);
        $progress->setRedrawFrequency(1);
        $progress->start();

        for ($i = 0; $i < 5; $i++) {
            $this->getContainer()->get('pon.dummy.news_category')->generate($output);
            $progress->advance();
        }
        $progress->finish();
    }

    public function dummyStore(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        for ($i = 0; $i < 10; $i++) {
            $output->writeln(sprintf("Begin dummy Store %s", $i + 1));
            $this->getContainer()->get('pon.dummy.store')->generate($output);
            $progress->advance();
            $output->writeln("");
            $output->writeln(sprintf("Finished dummy Store %s", $i + 1));
            $output->writeln("");
        }
        $progress->finish();
    }

    public function dummySegement(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 10);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        for ($i = 0; $i < 10; $i++) {
            $output->writeln(sprintf("Begin dummy Segement %s", $i + 1));
            $this->getContainer()->get('pon.dummy.segement')->generate($output);
            $progress->advance();
            $output->writeln("");
            $output->writeln(sprintf("Finished dummy Segement %s", $i + 1));
            $output->writeln("");
        }
        $progress->finish();
    }

    public function dummyPushSetting(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 50);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        for ($i = 0; $i < 50; $i++) {
            $output->writeln(sprintf("Begin dummy PushSetting %s", $i + 1));
            $this->getContainer()->get('pon.dummy.push_setting')->generate($output);
            $progress->advance();
            $output->writeln("");
            $output->writeln(sprintf("Finished dummy PushSetting %s", $i + 1));
            $output->writeln("");
        }
        $progress->finish();
    }

    public function dummyMessageDelivery(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 50);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        for ($i = 0; $i < 50; $i++) {
            $output->writeln(sprintf("Begin dummy MessageDelivery %s", $i + 1));
            $this->getContainer()->get('pon.dummy.message_delivery')->generate($output, $i + 1);
            $progress->advance();
            $output->writeln("");
            $output->writeln(sprintf("Finished dummy MessageDelivery %s", $i + 1));
            $output->writeln("");
        }
        $progress->finish();
    }

    public function dummyCoupon(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 50);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        for ($i = 0; $i < 50; $i++) {
            $output->writeln(sprintf("Begin dummy Coupon %s", $i + 1));
            $this->getContainer()->get('pon.dummy.coupon')->generate($output);
            $progress->advance();
            $output->writeln("");
            $output->writeln(sprintf("Finished dummy Coupon %s", $i + 1));
            $output->writeln("");
        }
        $progress->finish();
    }

    public function dummyNews(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 50);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        for ($i = 0; $i < 50; $i++) {
            $output->writeln(sprintf("Begin dummy News %s", $i + 1));
            $this->getContainer()->get('pon.dummy.news')->generate($output);
            $progress->advance();
            $output->writeln("");
            $output->writeln(sprintf("Finished dummy News %s", $i + 1));
            $output->writeln("");
        }
        $progress->finish();
    }

    public function dummyUseList(OutputInterface $output)
    {
        $progress = new ProgressBar($output, 100);
        $progress->setRedrawFrequency(1);
        $progress->start();
        for ($i = 0; $i < 100; $i++) {
            $this->getContainer()->get('pon.dummy.use_list')->generate($output);
            $progress->advance();
        }
        $progress->finish();
    }
}