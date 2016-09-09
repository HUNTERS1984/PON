<?php

namespace CoreBundle\Entity;

/**
 * StoreType
 */
class StoreType
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $iconUrl;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $stores;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stores = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return StoreType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set iconUrl
     *
     * @param string $iconUrl
     *
     * @return StoreType
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Get iconUrl
     *
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * Add store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return StoreType
     */
    public function addStore(\CoreBundle\Entity\Store $store)
    {
        $this->stores[] = $store;

        return $this;
    }

    /**
     * Remove store
     *
     * @param \CoreBundle\Entity\Store $store
     */
    public function removeStore(\CoreBundle\Entity\Store $store)
    {
        $this->stores->removeElement($store);
    }

    /**
     * Get stores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStores()
    {
        return $this->stores;
    }
}
