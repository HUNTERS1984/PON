<?php

namespace CoreBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    private $facebookId;

    /**
     * @var string
     */
    private $instagramId;

    /**
     * @var string
     */
    private $twitterId;


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
    private $posts;

    /**
     * @var integer
    */
    private $followNumber;

    /**
     * @var integer
    */
    private  $usedNumber;

    /**
     * @var integer
     */
    private $newsNumber;


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
     * @var string
    */
    private $webPath;

    /**
     * @var string
    */
    private $basePath;

    /**
     * @var UploadedFile
     */
    private $file;

    public function getAbsolutePath()
    {
        return null === $this->avatarUrl
            ? null
            : $this->getUploadRootDir().'/'.$this->avatarUrl;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        $this->webPath = $this->getWebPath();
    }

    public function getWebPath()
    {
        return null === $this->avatarUrl
            ? null
            : $this->basePath."/".$this->getUploadDir().'/'.$this->avatarUrl;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/appusers';
    }


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

    /**
     * Set file
     *
     * @param UploadedFile $file
     *
     * @return AppUser
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        $newFile = sprintf("%s.%s",$this->getId(),$this->getFile()->guessExtension());
        $uploadDir = $this->getUploadRootDir();
        $file = new Filesystem();
        if(!$file->exists($uploadDir)) {
            $file->mkdir($uploadDir);
        }
        $this->getFile()->move(
            $uploadDir,
            $newFile
        );

        $this->avatarUrl = $newFile;
        $this->webPath = $this->getWebPath();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $followLists;


    /**
     * Add followList
     *
     * @param \CoreBundle\Entity\FollowList $followList
     *
     * @return AppUser
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
     * @param string $facebookId
     * @return AppUser
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $twitterId
     * @return AppUser
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }
    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $tel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $stores;


    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return AppUser
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return AppUser
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return AppUser
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
     * Add store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return AppUser
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

    /**
     * @param string $instagramId
     * @return AppUser
     */
    public function setInstagramId(string $instagramId)
    {
        $this->instagramId = $instagramId;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstagramId()
    {
        return $this->instagramId;
    }

    /**
     * @param integer $followNumber
     * @return AppUser
     */
    public function setFollowNumber($followNumber)
    {
        $this->followNumber = $followNumber;
        return $this;
    }

    /**
     * @return integer
     */
    public function getFollowNumber()
    {
        return $this->followNumber;
    }

    /**
     * @param integer $usedNumber
     * @return AppUser
     */
    public function setUsedNumber($usedNumber)
    {
        $this->usedNumber = $usedNumber;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUsedNumber()
    {
        return $this->usedNumber;
    }

    /**
     * @param int $newsNumber
     * @return AppUser
     */
    public function setNewsNumber(int $newsNumber): AppUser
    {
        $this->newsNumber = $newsNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getNewsNumber(): int
    {
        return $this->newsNumber;
    }
}
