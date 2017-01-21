<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\UseList;
use Symfony\Component\EventDispatcher\Event;

class UseListEvents extends Event
{

    const PRE_CREATE = 'pon.event.use_list.pre_create';

    const POST_CREATE = 'pon.event.use_list.post_create';

    /**
     * @var UseList
     */
    protected $useList;

    /**
     * @param UseList $useList
     * @return UseListEvents
     */
    public function setUseList(UseList $useList): UseListEvents
    {
        $this->useList = $useList;

        return $this;
    }

    /**
     * @return UseList
     */
    public function getUseList(): UseList
    {
        return $this->useList;
    }
}
