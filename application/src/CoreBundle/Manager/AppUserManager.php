<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;

class AppUserManager extends AbstractManager
{

    public function dummy(AppUser $user)
    {
        $this->save($user);
    }
}