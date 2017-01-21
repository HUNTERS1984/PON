<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\AppUser;
use Symfony\Component\EventDispatcher\Event;

class AppUserEvents extends Event
{

    const PRE_CREATE = 'pon.event.app_user.pre_create';

    const POST_CREATE = 'pon.event.app_user.post_create';

    /**
     * @var AppUser
     */
    protected $appUser;

    /**
     * @param AppUser $appUser
     * @return AppUserEvents
     */
    public function setAppUser(AppUser $appUser): AppUserEvents
    {
        $this->appUser = $appUser;

        return $this;
    }

    /**
     * @return AppUser
     */
    public function getAppUser(): AppUser
    {
        return $this->appUser;
    }
}
