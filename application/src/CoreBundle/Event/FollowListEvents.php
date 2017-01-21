<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\FollowList;
use Symfony\Component\EventDispatcher\Event;

class FollowListEvents extends Event
{

    const PRE_CREATE = 'pon.event.follow_list.pre_create';

    const POST_CREATE = 'pon.event.follow_list.post_create';

    const PRE_DELETE = 'pon.event.follow_list.pre_delete';

    const POST_DELETE = 'pon.event.follow_list.post_delete';

    /**
     * @var FollowList
     */
    protected $followList;

    /**
     * @param FollowList $followList
     * @return FollowListEvents
     */
    public function setFollowList(FollowList $followList): FollowListEvents
    {
        $this->followList = $followList;

        return $this;
    }

    /**
     * @return FollowList
     */
    public function getFollowList(): FollowList
    {
        return $this->followList;
    }
}
