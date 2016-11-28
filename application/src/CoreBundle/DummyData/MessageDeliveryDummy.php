<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\MessageDelivery;
use CoreBundle\Entity\Store;
use CoreBundle\Manager\MessageDeliveryManager;
use CoreBundle\Manager\PushSettingManager;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;

class MessageDeliveryDummy extends BaseDummy implements IDummy
{
    /**@var PushSettingManager */
    private $pushSettingManager;

    /** @var MessageDeliveryManager $messageDeliveryManager*/
    private $messageDeliveryManager;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $status = $faker->numberBetween(0,3);

        $push = $this->pushSettingManager->findOneById($i);

        $messageDeliverry = new MessageDelivery();
        $messageDeliverry->setPushSetting($push);
        $messageDeliverry->setStatus($status);

        $messageDeliverry = $this->messageDeliveryManager->createMessageDelivery($messageDeliverry);
        return $messageDeliverry;
    }

    /**
     * @param PushSettingManager $pushSettingManager
     * @return MessageDeliveryDummy
     */
    public function setPushSettingManager($pushSettingManager)
    {
        $this->pushSettingManager = $pushSettingManager;
        return $this;
    }

    /**
     * @param MessageDeliveryManager $messageDeliveryManager
     * @return MessageDeliveryDummy
     */
    public function setMessageDeliveryManager($messageDeliveryManager)
    {
        $this->messageDeliveryManager = $messageDeliveryManager;
        return $this;
    }
}