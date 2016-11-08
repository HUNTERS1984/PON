<?php

namespace CoreBundle\Entity;

/**
 * UseList
 */
class UseList
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoreBundle\Entity\Coupon
     */
    private $coupon;

    /**
     * @var \CoreBundle\Entity\AppUser
     */
    private $appUser;

    /**
     * @var integer
    */
    private $status;


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
     * Set coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return UseList
     */
    public function setCoupon(\CoreBundle\Entity\Coupon $coupon = null)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return \CoreBundle\Entity\Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set appUser
     *
     * @param \CoreBundle\Entity\AppUser $appUser
     *
     * @return UseList
     */
    public function setAppUser(\CoreBundle\Entity\AppUser $appUser = null)
    {
        $this->appUser = $appUser;

        return $this;
    }

    /**
     * Get appUser
     *
     * @return \CoreBundle\Entity\AppUser
     */
    public function getAppUser()
    {
        return $this->appUser;
    }

    /**
     * @param mixed $status
     * @return UseList
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var string
     */
    private $code;


    /**
     * Set code
     *
     * @param string $code
     *
     * @return UseList
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return UseList
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
     * @var \DateTime
     */
    private $expiredTime;


    /**
     * Set expiredTime
     *
     * @param \DateTime $expiredTime
     *
     * @return UseList
     */
    public function setExpiredTime($expiredTime)
    {
        $this->expiredTime = $expiredTime;

        return $this;
    }

    /**
     * Get expiredTime
     *
     * @return \DateTime
     */
    public function getExpiredTime()
    {
        return $this->expiredTime;
    }
    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return UseList
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
     * @var \DateTime
     */
    private $requestedAt;


    /**
     * Set requestedAt
     *
     * @param \DateTime $requestedAt
     *
     * @return UseList
     */
    public function setRequestedAt($requestedAt)
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    /**
     * Get requestedAt
     *
     * @return \DateTime
     */
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }
}
