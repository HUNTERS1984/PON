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
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $json;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return SocialProfile
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set json
     *
     * @param string $json
     *
     * @return SocialProfile
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
}
