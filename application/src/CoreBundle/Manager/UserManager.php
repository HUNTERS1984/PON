<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\User;

class UserManager extends AbstractManager
{

    public function dummy(User $user)
    {
        $this->save($user);
    }
}