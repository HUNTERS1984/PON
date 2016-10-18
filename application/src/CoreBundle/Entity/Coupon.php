<?php

namespace CoreBundle\Entity;

/**
 * Coupon
 */
class Coupon
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
    private $expiredTime;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var boolean
     */
    private $needLogin;

    /**
     * @var string
     */
    private $code;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var array
     */
    private $couponType;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var integer
     */
    private $size;

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
    private $useLists;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $likeLists;

    /**
     * @var \CoreBundle\Entity\Store
     */
    private $store;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var boolean
     */
    private $like;

    /**
     * @var boolean
     */
    private $canUse;

    /**
     * @var array
     */
    private $couponPhotoUrls;

    /**
     * @var array
     */
    private $couponUserPhotoUrls;

    /**
     * @var integer
    */
    private $impression;

    /**
     * @var array
    */
    private $similarCoupons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->useLists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likeLists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Coupon
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
     * Set expiredTime
     *
     * @param \DateTime $expiredTime
     *
     * @return Coupon
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
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Coupon
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set needLogin
     *
     * @param boolean $needLogin
     *
     * @return Coupon
     */
    public function setNeedLogin($needLogin)
    {
        $this->needLogin = $needLogin;

        return $this;
    }

    /**
     * Get needLogin
     *
     * @return boolean
     */
    public function isNeedLogin()
    {
        return $this->needLogin;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Coupon
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
     * Set type
     *
     * @param integer $type
     *
     * @return Coupon
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Coupon
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Coupon
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Coupon
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Coupon
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
     * @return Coupon
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
     * @return Coupon
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
     * Add useList
     *
     * @param \CoreBundle\Entity\UseList $useList
     *
     * @return Coupon
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
     * Add likeList
     *
     * @param \CoreBundle\Entity\LikeList $likeList
     *
     * @return Coupon
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
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return Coupon
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
     * Add tag
     *
     * @param \CoreBundle\Entity\Tag $tag
     *
     * @return Coupon
     */
    public function addTag(\CoreBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \CoreBundle\Entity\Tag $tag
     */
    public function removeTag(\CoreBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param boolean $like
     * @return Coupon
     */
    public function setLike($like)
    {
        $this->like = $like;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLike()
    {
        return $this->like;
    }

    /**
     * @param array $couponType
     * @return Coupon
     */
    public function setCouponType($couponType)
    {
        $this->couponType = $couponType;
        return $this;
    }

    /**
     * @return array
     */
    public function getCouponType()
    {
        return $this->couponType;
    }

    /**
     * @param boolean $canUse
     * @return Coupon
     */
    public function setCanUse($canUse)
    {
        $this->canUse = $canUse;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCanUse()
    {
        return $this->canUse;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $couponPhotos;


    /**
     * Add couponPhoto
     *
     * @param \CoreBundle\Entity\CouponPhoto $couponPhoto
     *
     * @return Coupon
     */
    public function addCouponPhoto(\CoreBundle\Entity\CouponPhoto $couponPhoto)
    {
        $this->couponPhotos[] = $couponPhoto;

        return $this;
    }

    /**
     * Remove couponPhoto
     *
     * @param \CoreBundle\Entity\CouponPhoto $couponPhoto
     */
    public function removeCouponPhoto(\CoreBundle\Entity\CouponPhoto $couponPhoto)
    {
        $this->couponPhotos->removeElement($couponPhoto);
    }

    /**
     * Get couponPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCouponPhotos()
    {
        return $this->couponPhotos;
    }

    /**
     * @param array $couponPhotoUrls
     * @return Coupon
     */
    public function setCouponPhotoUrls($couponPhotoUrls)
    {
        $this->couponPhotoUrls = $couponPhotoUrls;
        return $this;
    }

    /**
     * @return array
     */
    public function getCouponPhotoUrls()
    {
        return $this->couponPhotoUrls;
    }

    /**
     * @return array
     */
    public function getCouponUserPhotoUrls()
    {
        return $this->couponUserPhotoUrls;
    }

    /**
     * @param array $couponUserPhotoUrls
     * @return Coupon
     */
    public function setCouponUserPhotoUrls($couponUserPhotoUrls)
    {
        $this->couponUserPhotoUrls = $couponUserPhotoUrls;
        return $this;
    }

    /**
     * @param mixed $impression
     * @return Coupon
     */
    public function setImpression($impression)
    {
        $this->impression = $impression;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImpression()
    {
        return $this->impression;
    }

    /**
     * @param array $similarCoupons
     * @return Coupon
     */
    public function setSimilarCoupons($similarCoupons)
    {
        $this->similarCoupons = $similarCoupons;
        return $this;
    }

    /**
     * @return array
     */
    public function getSimilarCoupons()
    {
        return $this->similarCoupons;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $couponUserPhotos;


    /**
     * Get needLogin
     *
     * @return boolean
     */
    public function getNeedLogin()
    {
        return $this->needLogin;
    }

    /**
     * Add couponUserPhoto
     *
     * @param \CoreBundle\Entity\CouponUserPhoto $couponUserPhoto
     *
     * @return Coupon
     */
    public function addCouponUserPhoto(\CoreBundle\Entity\CouponUserPhoto $couponUserPhoto)
    {
        $this->couponUserPhotos[] = $couponUserPhoto;

        return $this;
    }

    /**
     * Remove couponUserPhoto
     *
     * @param \CoreBundle\Entity\CouponUserPhoto $couponUserPhoto
     */
    public function removeCouponUserPhoto(\CoreBundle\Entity\CouponUserPhoto $couponUserPhoto)
    {
        $this->couponUserPhotos->removeElement($couponUserPhoto);
    }

    /**
     * Get couponUserPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCouponUserPhotos()
    {
        return $this->couponUserPhotos;
    }
}
