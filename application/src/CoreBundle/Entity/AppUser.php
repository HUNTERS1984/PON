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
     * @var string
     */
    private $appUserId;

    /**
     * @var string
     */
    private $role;

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
     * @param string $role
     * @return AppUser
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
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
    private $imageFile;

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
    private $company;

    /**
     * @var string
     */
    private $tel;

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
    /**
     * @var \CoreBundle\Entity\Store
     */
    private $store;


    /**
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return AppUser
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
     * @param string $appUserId
     * @return AppUser
     */
    public function setAppUserId(string $appUserId)
    {
        $this->appUserId = $appUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAppUserId()
    {
        return $this->appUserId;
    }

    /**
     * @param UploadedFile $imageFile
     * @return AppUser
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
}
