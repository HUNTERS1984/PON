<?php

namespace CoreBundle\Entity;

/**
 * SocialProfile
 */
class SocialProfile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $socialType;

    /**
     * @var string
     */
    private $socialId;

    /**
     * @var string
     */
    private $socialToken;

    /**
     * @var string
     */
    private $socialSecret;

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
     * @var \CoreBundle\Entity\AppUser
     */
    private $appUser;


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
     * Set socialType
     *
     * @param integer $socialType
     *
     * @return SocialProfile
     */
    public function setSocialType($socialType)
    {
        $this->socialType = $socialType;

        return $this;
    }

    /**
     * Get socialType
     *
     * @return integer
     */
    public function getSocialType()
    {
        return $this->socialType;
    }

    /**
     * Set socialId
     *
     * @param string $socialId
     *
     * @return SocialProfile
     */
    public function setSocialId($socialId)
    {
        $this->socialId = $socialId;

        return $this;
    }

    /**
     * Get socialId
     *
     * @return string
     */
    public function getSocialId()
    {
        return $this->socialId;
    }

    /**
     * Set socialToken
     *
     * @param string $socialToken
     *
     * @return SocialProfile
     */
    public function setSocialToken($socialToken)
    {
        $this->socialToken = $socialToken;

        return $this;
    }

    /**
     * Get socialToken
     *
     * @return string
     */
    public function getSocialToken()
    {
        return $this->socialToken;
    }

    /**
     * Set socialSecret
     *
     * @param string $socialSecret
     *
     * @return SocialProfile
     */
    public function setSocialSecret($socialSecret)
    {
        $this->socialSecret = $socialSecret;

        return $this;
    }

    /**
     * Get socialSecret
     *
     * @return string
     */
    public function getSocialSecret()
    {
        return $this->socialSecret;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SocialProfile
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
     * @return SocialProfile
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
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return SocialProfile
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Set appUser
     *
     * @param \CoreBundle\Entity\AppUser $appUser
     *
     * @return SocialProfile
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
     * @var \DateTime
     */
    private $requestedAt;


    /**
     * Set requestedAt
     *
     * @param \DateTime $requestedAt
     *
     * @return SocialProfile
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
    /**
     * @var boolean
     */
    private $error;


    /**
     * Set error
     *
     * @param boolean $error
     *
     * @return SocialProfile
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error
     *
     * @return boolean
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * Get error
     *
     * @return boolean
     */
    public function getError()
    {
        return $this->error;
    }
}
