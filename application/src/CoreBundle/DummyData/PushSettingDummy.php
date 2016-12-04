<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\PushSetting;
use CoreBundle\Entity\Store;
use CoreBundle\Manager\PushSettingManager;
use CoreBundle\Manager\SegementManager;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;

class PushSettingDummy extends BaseDummy implements IDummy
{
    /**@var StoreManager */
    private $storeManager;

    /**@var SegementManager */
    private $segementManager;

    /** @var PushSettingManager $pushSettingManager */
    private $pushSettingManager;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $storeId = $faker->numberBetween(1, 10);
        $segementId = $faker->numberBetween(1, 10);
        $title = $faker->name;
        $message = $faker->paragraph;
        $status = $faker->numberBetween(0, 1);
        $json = 'a:3:{s:1:"a";i:1;s:1:"b";i:2;s:1:"c";i:3;}';
        $store = $this->storeManager->findOneById($storeId);
        $segement = $this->segementManager->findOneById($segementId);

        $pushSetting = new PushSetting();
        $pushSetting->setTitle($title);
        $pushSetting->setStore($store);
        $pushSetting->setSegement($segement);
        $pushSetting->setMessage($message);
        $pushSetting->setStatus($status);
        $pushSetting->setJson($json);
        $type = $faker->randomElement(['now', 'special']);

        $now = new \DateTime();
        if($type == 'special') {
            $days = $faker->numberBetween(1, 10);
            $now->modify("+$days days");
            $pushSetting->setDeliveryTime($now);
        }else{
            $pushSetting->setDeliveryTime($now);
        }
        $pushSetting->setType($type);


        $pushSetting = $this->pushSettingManager->createPushSetting($pushSetting);
        return $pushSetting;
    }

    /**
     * @param StoreManager $storeManager
     * @return PushSettingDummy
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }

    /**
     * @param SegementManager $segementManager
     * @return PushSettingDummy
     */
    public function setSegementManager($segementManager)
    {
        $this->segementManager = $segementManager;
        return $this;
    }

    /**
     * @param PushSettingManager $pushSettingManager
     * @return PushSettingDummy
     */
    public function setPushSettingManager($pushSettingManager)
    {
        $this->pushSettingManager = $pushSettingManager;
        return $this;
    }
}