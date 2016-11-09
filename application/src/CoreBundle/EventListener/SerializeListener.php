<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\CouponUserPhoto;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Entity\UseList;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\FollowListManager;
use CoreBundle\Manager\LikeListManager;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Manager\UseListManager;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class SerializeListener implements EventSubscriberInterface
{
    /**
     * @var $securityContext
     */
    private $securityContext;

    /**
     * @var StoreManager $storeManager
     */
    private $storeManager;

    /**
     * @var CouponManager $couponManager
     */
    private $couponManager;

    /**
     * @var UseListManager $useListManager
     */
    private $useListManager;

    /**
     * @var LikeListManager $likeListManager
     */
    private $likeListManager;

    /**
     * @var FollowListManager $followListManager
     */
    private $followListManager;

    /**
     * @var AppUserManager $appUserManager
     */
    private $appUserManager;

    /**
     * @var array $couponTypes ;
     */
    private $couponTypes;

    /**
     * @param PreSerializeEvent $event
     */
    public function onPreSerialize(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        if ($object instanceof Store) {
            $this->preStoreSerialize($object);
        }

        if ($object instanceof Coupon) {
            $this->preCouponSerialize($object);
        }

        if($object instanceof Category) {
            $this->preCategorySerialize($object);
        }
    }


    /**
     * getUser
     *
     * @return null|AppUser
     */
    public function getUser()
    {
        if (null === $token = $this->securityContext->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    /**
     * @param Store $store
     */
    public function preStoreSerialize(Store $store)
    {
        $this->setFollow($store);
        $this->setStorePhoto($store);
    }

    /**
     * @param Category $category
     */
    public function preCategorySerialize(Category $category)
    {
        $this->setShopCount($category);
    }

    /**
     * @param Category $category
     */
    public function setShopCount(Category &$category)
    {
        $shopCount = $this->storeManager->getShopCount($category);
        $category->setShopCount($shopCount);
    }

    /**
     * @param Coupon $coupon
     */
    public function preCouponSerialize(Coupon $coupon)
    {
        $this->setLike($coupon);
        $this->setCouponType($coupon);
        $this->setCanUse($coupon);
        $this->setCode($coupon);
        $this->setCouponPhoto($coupon);
        $this->setCouponUserPhoto($coupon);
        $this->setSimilarCoupon($coupon);
    }

    /**
     * @param Store $store
     */
    public function setStorePhoto(Store $store)
    {
        $photoUrls = array_map(function (StorePhoto $storePhoto) {
            return $storePhoto->getPhoto()->getImageUrl();
        }, $store->getStorePhotos()->toArray());
        $store->setStorePhotoUrls($photoUrls);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCouponPhoto(Coupon $coupon)
    {
        $photoUrls = array_map(function (CouponPhoto $couponPhoto) {
            return $couponPhoto->getPhoto()->getImageUrl();
        }, $coupon->getCouponPhotos()->toArray());
        $coupon->setCouponPhotoUrls($photoUrls);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCouponUserPhoto(Coupon $coupon)
    {
        $photoUrls = array_map(function (CouponUserPhoto $couponUserPhoto) {
            return $couponUserPhoto->getPhoto()->getImageUrl();
        }, $coupon->getCouponUserPhotos()->toArray());
        $coupon->setCouponUserPhotoUrls($photoUrls);
    }

    /**
     * @param Coupon $coupon
     */
    public function setSimilarCoupon(Coupon $coupon)
    {
        $coupons = $this->couponManager->getSimilarCoupon($coupon);
        $user = $this->getUser();
        $similarCoupons = array_map(function (Coupon $coupon) use ($user){
            $isLike = $this->likeListManager->isLike($user, $coupon);
            $isCanUse = $this->useListManager->isCanUse($user, $coupon);
            $type = $coupon->getType();
            $couponType = ['id' => $type, 'name' => $this->couponTypes[$type]];
            return [
                'id' => $coupon->getId(),
                'title' => $coupon->getTitle(),
                'image_url' => $coupon->getImageUrl(),
                'expired_time' => $coupon->getExpiredTime(),
                'is_like' => $isLike,
                'need_login' => $coupon->isNeedLogin(),
                'can_use' => $isCanUse,
                'coupon_type' => $couponType
            ];
        }, $coupons);
        $coupon->setSimilarCoupons($similarCoupons);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCanUse(Coupon &$coupon)
    {
        $user = $this->getUser();
        $isCanUse = $this->useListManager->isCanUse($user, $coupon);
        $coupon->setCanUse($isCanUse);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCode(Coupon &$coupon)
    {
        $user = $this->getUser();
        $code = $this->useListManager->getCode($user, $coupon);
        $coupon->setCode($code);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCouponType(Coupon &$coupon)
    {
        $type = $coupon->getType();
        $coupon->setCouponType(['id' => $type, 'name' => $this->couponTypes[$type]]);
    }

    /**
     * @param Coupon $coupon
     */
    public function setLike(Coupon &$coupon)
    {
        $user = $this->getUser();
        $isLike = $this->likeListManager->isLike($user, $coupon);
        $coupon->setLike($isLike);
    }

    /**
     * @param Store $store
     */
    public function setFollow(Store $store)
    {
        if (!$user = $this->getUser()) {
            $store->setFollow(false);
            return;
        }

        $isFollow = $this->followListManager->isFollow($user, $store);
        $store->setFollow($isFollow);
    }

    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' => 'json'),
     *         array(...),
     *     )
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.pre_serialize', 'method' => 'onPreSerialize')
        );
    }

    /**
     * @param mixed $securityContext
     * @return SerializeListener
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
        return $this;
    }

    /**
     * @param StoreManager $storeManager
     * @return SerializeListener
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }

    /**
     * @param mixed $couponManager
     * @return SerializeListener
     */
    public function setCouponManager($couponManager)
    {
        $this->couponManager = $couponManager;
        return $this;
    }

    /**
     * @param mixed $couponTypes
     * @return SerializeListener
     */
    public function setCouponTypes($couponTypes)
    {
        $this->couponTypes = $couponTypes;
        return $this;
    }

    /**
     * @param AppUserManager $appUserManager
     * @return SerializeListener
     */
    public function setAppUserManager($appUserManager)
    {
        $this->appUserManager = $appUserManager;
        return $this;
    }

    /**
     * @param UseListManager $useListManager
     * @return SerializeListener
     */
    public function setUseListManager($useListManager)
    {
        $this->useListManager = $useListManager;
        return $this;
    }

    /**
     * @param LikeListManager $likeListManager
     * @return SerializeListener
     */
    public function setLikeListManager($likeListManager)
    {
        $this->likeListManager = $likeListManager;
        return $this;
    }

    /**
     * @param FollowListManager $followListManager
     * @return SerializeListener
     */
    public function setFollowListManager($followListManager)
    {
        $this->followListManager = $followListManager;
        return $this;
    }
}
