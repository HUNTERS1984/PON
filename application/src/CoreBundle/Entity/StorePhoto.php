<?php

namespace CoreBundle\Entity;

/**
 * StorePhoto
 */
class StorePhoto
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var \CoreBundle\Entity\Store
     */
    private $store;

    /**
     * @var \CoreBundle\Entity\Photo
     */
    private $photo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return StorePhoto
     */
    public function setStore(\CoreBundle\Entity\Store $store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \CoreBundle\Entity\Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set photo
     *
     * @param \CoreBundle\Entity\Photo $photo
     *
     * @return StorePhoto
     */
    public function setPhoto(\CoreBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \CoreBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
