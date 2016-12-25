<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\PushSetting;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvents extends Event
{

    const PRE_CREATE = 'pon.event.notification.pre_create';

    const POST_CREATE = 'pon.event.notification.post_create';

    /**
     * @var PushSetting
     */
    protected $pushSetting;

    /**
     * @param PushSetting $pushSetting
     * @return NotificationEvents
     */
    public function setPushSetting(PushSetting $pushSetting)
    {
        $this->pushSetting = $pushSetting;
        return $this;
    }

    /**
     * @return PushSetting
     */
    public function getPushSetting()
    {
        return $this->pushSetting;
    }
}
