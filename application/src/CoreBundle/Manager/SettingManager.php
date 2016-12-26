<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Setting;

class SettingManager extends AbstractManager
{

    /**
     * @param Setting $setting
     *
     * @return Setting
     */
    public function saveSetting(Setting $setting)
    {
        $setting->setUpdatedAt(new \DateTime());
        return $this->save($setting);
    }

    /**
     * get Setting
     *
     * @param $type
     * @return null | Setting
     */
    public function getSetting($type)
    {
        return $this->findOneBy(['type' => $type]);
    }
}