<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\LikeList;
use Symfony\Component\EventDispatcher\Event;

class LikeListEvents extends Event
{

    const PRE_CREATE = 'pon.event.like_list.pre_create';

    const POST_CREATE = 'pon.event.like_list.post_create';

    /**
     * @var LikeList
     */
    protected $likeList;

    /**
     * @param LikeList $likeList
     * @return LikeListEvents
     */
    public function setLikeList(LikeList $likeList): LikeListEvents
    {
        $this->likeList = $likeList;

        return $this;
    }

    /**
     * @return LikeList
     */
    public function getLikeList(): LikeList
    {
        return $this->likeList;
    }
}
