<?php

namespace CoreBundle\Entity;

/**
 * PushSetting
 */
class PushSetting
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
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $json;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $deliveryTime;

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
    private $messageDeliveries;

    /**
     * @var \CoreBundle\Entity\Store
     */
    private $store;

    /**
     * @var \CoreBundle\Entity\Segement
     */
    private $segement;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messageDeliveries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PushSetting
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
     * Set message
     *
     * @param string $message
     *
     * @return PushSetting
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set json
     *
     * @param string $json
     *
     * @return PushSetting
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get json
     *
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return PushSetting
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set time
     *
     * @param \DateTime $deliveryTime
     *
     * @return PushSetting
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PushSetting
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
     * @return PushSetting
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
     * @return PushSetting
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
     * Add messageDelivery
     *
     * @param \CoreBundle\Entity\MessageDelivery $messageDelivery
     *
     * @return PushSetting
     */
    public function addMessageDelivery(\CoreBundle\Entity\MessageDelivery $messageDelivery)
    {
        $this->messageDeliveries[] = $messageDelivery;

        return $this;
    }

    /**
     * Remove messageDelivery
     *
     * @param \CoreBundle\Entity\MessageDelivery $messageDelivery
     */
    public function removeMessageDelivery(\CoreBundle\Entity\MessageDelivery $messageDelivery)
    {
        $this->messageDeliveries->removeElement($messageDelivery);
    }

    /**
     * Get messageDeliveries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessageDeliveries()
    {
        return $this->messageDeliveries;
    }

    /**
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return PushSetting
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
     * Set segement
     *
     * @param \CoreBundle\Entity\Segement $segement
     *
     * @return PushSetting
     */
    public function setSegement(\CoreBundle\Entity\Segement $segement = null)
    {
        $this->segement = $segement;

        return $this;
    }

    /**
     * Get segement
     *
     * @return \CoreBundle\Entity\Segement
     */
    public function getSegement()
    {
        return $this->segement;
    }

    /**
     * @param string $type
     * @return PushSetting
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

