<?php

namespace CoreBundle\Entity;

/**
 * Segement
 */
class Segement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pushSettings;


    /**
     * @var \CoreBundle\Entity\Store
     */
    private $store;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pushSettings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Segement
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Segement
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Segement
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Segement
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Add pushSetting
     *
     * @param \CoreBundle\Entity\PushSetting $pushSetting
     *
     * @return Segement
     */
    public function addPushSetting(\CoreBundle\Entity\PushSetting $pushSetting)
    {
        $this->pushSettings[] = $pushSetting;

        return $this;
    }

    /**
     * Remove pushSetting
     *
     * @param \CoreBundle\Entity\PushSetting $pushSetting
     */
    public function removePushSetting(\CoreBundle\Entity\PushSetting $pushSetting)
    {
        $this->pushSettings->removeElement($pushSetting);
    }

    /**
     * Get pushSettings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPushSettings()
    {
        return $this->pushSettings;
    }

    /**
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return Segement
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
}

