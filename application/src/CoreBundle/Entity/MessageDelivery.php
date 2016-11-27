<?php

namespace CoreBundle\Entity;

/**
 * MessageDelivery
 */
class MessageDelivery
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \CoreBundle\Entity\PushSetting
     */
    private $pushSetting;

    /**
     * @var \CoreBundle\Entity\Segement
     */
    private $segement;


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
     * Set status
     *
     * @param string $status
     *
     * @return MessageDelivery
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
     * Set pushSetting
     *
     * @param \CoreBundle\Entity\PushSetting $pushSetting
     *
     * @return MessageDelivery
     */
    public function setPushSetting(\CoreBundle\Entity\PushSetting $pushSetting = null)
    {
        $this->pushSetting = $pushSetting;

        return $this;
    }

    /**
     * Get pushSetting
     *
     * @return \CoreBundle\Entity\PushSetting
     */
    public function getPushSetting()
    {
        return $this->pushSetting;
    }

    /**
     * Set segement
     *
     * @param \CoreBundle\Entity\Segement $segement
     *
     * @return MessageDelivery
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
}

