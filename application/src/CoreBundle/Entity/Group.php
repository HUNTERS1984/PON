<?php

namespace CoreBundle\Entity;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Group
 */
class Group extends BaseGroup
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;


    /**
     * Add user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(\CoreBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \CoreBundle\Entity\User $user
     */
    public function removeUser(\CoreBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
