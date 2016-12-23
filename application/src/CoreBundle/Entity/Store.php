<?php

namespace CoreBundle\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Store
 */
class Store
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
    private $operationStartTime;

    /**
     * @var \DateTime
     */
    private $operationEndTime;

    /**
     * @var string
     */
    private $avatarUrl;

    /**
     * @var string
     */
    private $tel;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $closeDate;

    /**
     * @var integer
     */
    private $aveBill;

    /**
     * @var string
     */
    private $helpText;

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
    private $followLists;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pushSettings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $news;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $coupons;

    /**
     * @var \CoreBundle\Entity\Category
     */
    private $category;

    /**
     * @var string
    */
    private $location;

    /**
     * @var boolean
    */
    private $follow;

    /**
     * @var array
    */
    private $storePhotoUrls;

    /**
     * @var UploadedFile
     */
    private $imageFile;

    /**
     * @var string
     */
    private $storeId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->followLists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pushSettings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coupons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Store
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
     * Set operationStartTime
     *
     * @param \DateTime $operationStartTime
     *
     * @return Store
     */
    public function setOperationStartTime($operationStartTime)
    {
        $this->operationStartTime = $operationStartTime;

        return $this;
    }

    /**
     * Get operationStartTime
     *
     * @return \DateTime
     */
    public function getOperationStartTime()
    {
        return $this->operationStartTime;
    }

    /**
     * Set operationEndTime
     *
     * @param \DateTime $operationEndTime
     *
     * @return Store
     */
    public function setOperationEndTime($operationEndTime)
    {
        $this->operationEndTime = $operationEndTime;

        return $this;
    }

    /**
     * Get operationEndTime
     *
     * @return \DateTime
     */
    public function getOperationEndTime()
    {
        return $this->operationEndTime;
    }

    /**
     * Set avatarUrl
     *
     * @param string $avatarUrl
     *
     * @return Store
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

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Store
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Store
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Store
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Store
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
     * Set closeDate
     *
     * @param string $closeDate
     *
     * @return Store
     */
    public function setCloseDate($closeDate)
    {
        $this->closeDate = $closeDate;

        return $this;
    }

    /**
     * Get closeDate
     *
     * @return string
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * Set aveBill
     *
     * @param integer $aveBill
     *
     * @return Store
     */
    public function setAveBill($aveBill)
    {
        $this->aveBill = $aveBill;

        return $this;
    }

    /**
     * Get aveBill
     *
     * @return integer
     */
    public function getAveBill()
    {
        return $this->aveBill;
    }

    /**
     * Set helpText
     *
     * @param string $helpText
     *
     * @return Store
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;

        return $this;
    }

    /**
     * Get helpText
     *
     * @return string
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Store
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
     * @return Store
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
     * @return Store
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
     * Add followList
     *
     * @param \CoreBundle\Entity\FollowList $followList
     *
     * @return Store
     */
    public function addFollowList(\CoreBundle\Entity\FollowList $followList)
    {
        $this->followLists[] = $followList;

        return $this;
    }

    /**
     * Remove followList
     *
     * @param \CoreBundle\Entity\FollowList $followList
     */
    public function removeFollowList(\CoreBundle\Entity\FollowList $followList)
    {
        $this->followLists->removeElement($followList);
    }

    /**
     * Get followLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowLists()
    {
        return $this->followLists;
    }

    /**
     * Add pushSetting
     *
     * @param \CoreBundle\Entity\PushSetting $pushSetting
     *
     * @return Store
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
     * Add news
     *
     * @param \CoreBundle\Entity\News $news
     *
     * @return Store
     */
    public function addNews(\CoreBundle\Entity\News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param \CoreBundle\Entity\News $news
     */
    public function removeNews(\CoreBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return Store
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
     * Set category
     *
     * @param \CoreBundle\Entity\Category $category
     *
     * @return Store
     */
    public function setCategory(\CoreBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \CoreBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->latitude.','.$this->longitude;
    }

    /**
     * @param string $location
     * @return Store
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param boolean $follow
     * @return Store
     */
    public function setFollow($follow)
    {
        $this->follow = $follow;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isFollow()
    {
        return $this->follow;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $storePhotos;


    /**
     * Add storePhoto
     *
     * @param \CoreBundle\Entity\StorePhoto $storePhoto
     *
     * @return Store
     */
    public function addStorePhoto(\CoreBundle\Entity\StorePhoto $storePhoto)
    {
        $this->storePhotos[] = $storePhoto;

        return $this;
    }

    /**
     * Remove storePhoto
     *
     * @param \CoreBundle\Entity\StorePhoto $storePhoto
     */
    public function removeStorePhoto(\CoreBundle\Entity\StorePhoto $storePhoto)
    {
        $this->storePhotos->removeElement($storePhoto);
    }

    /**
     * Get storePhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStorePhotos()
    {
        return $this->storePhotos;
    }

    /**
     * @param mixed $storePhotoUrls
     * @return Store
     */
    public function setStorePhotoUrls($storePhotoUrls)
    {
        $this->storePhotoUrls = $storePhotoUrls;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStorePhotoUrls()
    {
        return $this->storePhotoUrls;
    }

    /**
     * @var integer
     */
    private $impression;


    /**
     * Set impression
     *
     * @param integer $impression
     *
     * @return Store
     */
    public function setImpression($impression)
    {
        $this->impression = $impression;

        return $this;
    }

    /**
     * Get impression
     *
     * @return integer
     */
    public function getImpression()
    {
        return $this->impression;
    }

    /**
     * @param int $id
     * @return Store
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $segements;


    /**
     * Add segement
     *
     * @param \CoreBundle\Entity\Segement $segement
     *
     * @return Store
     */
    public function addSegement(\CoreBundle\Entity\Segement $segement)
    {
        $this->segements[] = $segement;

        return $this;
    }

    /**
     * Remove segement
     *
     * @param \CoreBundle\Entity\Segement $segement
     */
    public function removeSegement(\CoreBundle\Entity\Segement $segement)
    {
        $this->segements->removeElement($segement);
    }

    /**
     * Get segements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSegements()
    {
        return $this->segements;
    }

    /**
     * @param UploadedFile $imageFile
     * @return Store
     */
    public function setImageFile(UploadedFile $imageFile)
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $storeId
     * @return Store
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->storeId;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $appUsers;


    /**
     * Add appUser
     *
     * @param \CoreBundle\Entity\AppUser $appUser
     *
     * @return Store
     */
    public function addAppUser(\CoreBundle\Entity\AppUser $appUser)
    {
        $this->appUsers[] = $appUser;

        return $this;
    }

    /**
     * Remove appUser
     *
     * @param \CoreBundle\Entity\AppUser $appUser
     */
    public function removeAppUser(\CoreBundle\Entity\AppUser $appUser)
    {
        $this->appUsers->removeElement($appUser);
    }

    /**
     * Get appUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAppUsers()
    {
        return $this->appUsers;
    }
    /**
     * @var string
     */
    private $link;


    /**
     * Set link
     *
     * @param string $link
     *
     * @return Store
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
