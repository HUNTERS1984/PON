<?php

namespace CoreBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * AppUser
 */
class AppUser extends BaseUser
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    private $temporaryHash;

    /**
     * @var string
     */
    private $androidPushKey;

    /**
     * @var string
     */
    private $applePushKey;

    /**
     * @var string
     */
    private $role;

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
    private $socialProfiles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $appUserProfiles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;


    /**
     * Set temporaryHash
     *
     * @param string $temporaryHash
     *
     * @return AppUser
     */
    public function setTemporaryHash($temporaryHash)
    {
        $this->temporaryHash = $temporaryHash;

        return $this;
    }

    /**
     * Get temporaryHash
     *
     * @return string
     */
    public function getTemporaryHash()
    {
        return $this->temporaryHash;
    }

    /**
     * Set androidPushKey
     *
     * @param string $androidPushKey
     *
     * @return AppUser
     */
    public function setAndroidPushKey($androidPushKey)
    {
        $this->androidPushKey = $androidPushKey;

        return $this;
    }

    /**
     * Get androidPushKey
     *
     * @return string
     */
    public function getAndroidPushKey()
    {
        return $this->androidPushKey;
    }

    /**
     * Set applePushKey
     *
     * @param string $applePushKey
     *
     * @return AppUser
     */
    public function setApplePushKey($applePushKey)
    {
        $this->applePushKey = $applePushKey;

        return $this;
    }

    /**
     * Get applePushKey
     *
     * @return string
     */
    public function getApplePushKey()
    {
        return $this->applePushKey;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return AppUser
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AppUser
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
     * @return AppUser
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
     * @return AppUser
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
     * Add socialProfile
     *
     * @param \CoreBundle\Entity\SocialProfile $socialProfile
     *
     * @return AppUser
     */
    public function addSocialProfile(\CoreBundle\Entity\SocialProfile $socialProfile)
    {
        $this->socialProfiles[] = $socialProfile;

        return $this;
    }

    /**
     * Remove socialProfile
     *
     * @param \CoreBundle\Entity\SocialProfile $socialProfile
     */
    public function removeSocialProfile(\CoreBundle\Entity\SocialProfile $socialProfile)
    {
        $this->socialProfiles->removeElement($socialProfile);
    }

    /**
     * Get socialProfiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSocialProfiles()
    {
        return $this->socialProfiles;
    }

    /**
     * Add appUserProfile
     *
     * @param \CoreBundle\Entity\AppUserProfile $appUserProfile
     *
     * @return AppUser
     */
    public function addAppUserProfile(\CoreBundle\Entity\AppUserProfile $appUserProfile)
    {
        $this->appUserProfiles[] = $appUserProfile;

        return $this;
    }

    /**
     * Remove appUserProfile
     *
     * @param \CoreBundle\Entity\AppUserProfile $appUserProfile
     */
    public function removeAppUserProfile(\CoreBundle\Entity\AppUserProfile $appUserProfile)
    {
        $this->appUserProfiles->removeElement($appUserProfile);
    }

    /**
     * Get appUserProfiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAppUserProfiles()
    {
        return $this->appUserProfiles;
    }

    /**
     * Add post
     *
     * @param \CoreBundle\Entity\Post $post
     *
     * @return AppUser
     */
    public function addPost(\CoreBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \CoreBundle\Entity\Post $post
     */
    public function removePost(\CoreBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $coupons;


    /**
     * Add coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return AppUser
     */
    public function addCoupon(\CoreBundle\Entity\Coupon $coupon)
    {
        $this->coupons[] = $coupon;

        return $this;
    }

    /**
     * Remove coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     */
    public function removeCoupon(\CoreBundle\Entity\Coupon $coupon)
    {
        $this->coupons->removeElement($coupon);
    }

    /**
     * Get coupons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoupons()
    {
        return $this->coupons;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $useLists;


    /**
     * Add useList
     *
     * @param \CoreBundle\Entity\UseList $useList
     *
     * @return AppUser
     */
    public function addUseList(\CoreBundle\Entity\UseList $useList)
    {
        $this->useLists[] = $useList;

        return $this;
    }

    /**
     * Remove useList
     *
     * @param \CoreBundle\Entity\UseList $useList
     */
    public function removeUseList(\CoreBundle\Entity\UseList $useList)
    {
        $this->useLists->removeElement($useList);
    }

    /**
     * Get useLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUseLists()
    {
        return $this->useLists;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $likeLists;


    /**
     * Add likeList
     *
     * @param \CoreBundle\Entity\LikeList $likeList
     *
     * @return AppUser
     */
    public function addLikeList(\CoreBundle\Entity\LikeList $likeList)
    {
        $this->likeLists[] = $likeList;

        return $this;
    }

    /**
     * Remove likeList
     *
     * @param \CoreBundle\Entity\LikeList $likeList
     */
    public function removeLikeList(\CoreBundle\Entity\LikeList $likeList)
    {
        $this->likeLists->removeElement($likeList);
    }

    /**
     * Get likeLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikeLists()
    {
        return $this->likeLists;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $accessTokens;


    /**
     * Add accessToken
     *
     * @param \CoreBundle\Entity\AccessToken $accessToken
     *
     * @return AppUser
     */
    public function addAccessToken(\CoreBundle\Entity\AccessToken $accessToken)
    {
        $this->accessTokens[] = $accessToken;

        return $this;
    }

    /**
     * Remove accessToken
     *
     * @param \CoreBundle\Entity\AccessToken $accessToken
     */
    public function removeAccessToken(\CoreBundle\Entity\AccessToken $accessToken)
    {
        $this->accessTokens->removeElement($accessToken);
    }

    /**
     * Get accessTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccessTokens()
    {
        return $this->accessTokens;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $refreshTokens;


    /**
     * Add refreshToken
     *
     * @param \CoreBundle\Entity\RefreshToken $refreshToken
     *
     * @return AppUser
     */
    public function addRefreshToken(\CoreBundle\Entity\RefreshToken $refreshToken)
    {
        $this->refreshTokens[] = $refreshToken;

        return $this;
    }

    /**
     * Remove refreshToken
     *
     * @param \CoreBundle\Entity\RefreshToken $refreshToken
     */
    public function removeRefreshToken(\CoreBundle\Entity\RefreshToken $refreshToken)
    {
        $this->refreshTokens->removeElement($refreshToken);
    }

    /**
     * Get refreshTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRefreshTokens()
    {
        return $this->refreshTokens;
    }
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $gender;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $avatarUrl;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return AppUser
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
     * Set gender
     *
     * @param integer $gender
     *
     * @return AppUser
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return AppUser
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set avatarUrl
     *
     * @param string $avatarUrl
     *
     * @return AppUser
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Get avatarUrl
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }
}
