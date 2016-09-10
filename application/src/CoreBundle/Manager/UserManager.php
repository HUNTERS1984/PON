<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\User;

class UserManager extends AbstractManager
{

    public function dummy(User $user)
    {
        $this->save($user);
    }

    public function saveUser(User $user)
    {
        $this->save($user);
    }

    public function getUsers()
    {
        return $this->findAll();
    }

    public function getUser($id)
    {
        return $this->findOneById($id);
    }
    
    public function deletUser(User $user)
    {
        $this->delete($user);
    }
}