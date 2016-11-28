<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\CouponUserPhoto;
use CoreBundle\Entity\Segement;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Entity\UseList;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\FollowListManager;
use CoreBundle\Manager\LikeListManager;
use CoreBundle\Manager\SegementManager;
use CoreBundle\Manager\PushSettingManager;
use CoreBundle\Manager\MessageDeliveryManager;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Manager\UseListManager;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;

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
     * @var segementManager $segementManager
     */
    private $segementManager;

    /**
     * @var pushSettingManager $pushSettingManager
     */
    private $pushSettingManager;

    /**
     * @var messageDeliveryManager $messageDeliveryManager
     */
    private $messageDeliveryManager;

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
     * @var Router
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var string
     */
    private $baseCouponAvatarPath;

    /**
     * @var string
     */
    private $baseCategoryAvatarPath;

    /**
     * @var string
     */
    private $baseStoreAvatarPath;

    /**
     * @var string
     */
    private $baseImagePath;

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

        if ($object instanceof Category) {
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
        $this->setAvatarStore($store);
    }

    /**
     * @param Category $category
     */
    public function preCategorySerialize(Category $category)
    {
        $this->setShopCount($category);
        $this->setAvatarCategory($category);
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
        $this->setLink($coupon);
        $this->setCouponPhoto($coupon);
        $this->setAvatarCoupon($coupon);
        $this->setCouponUserPhoto($coupon);
        $this->setSimilarCoupon($coupon);
    }

    /**
     * @param Coupon $coupon
     */
    public function setAvatarCoupon(Coupon $coupon)
    {
        $avatarUrl = sprintf("%s/%s%s", $this->getUrl(), $this->baseCouponAvatarPath, $coupon->getImageUrl());
        $coupon->setImageUrl($avatarUrl);
    }

    /**
     * @param Category $category
     */
    public function setAvatarCategory(Category $category)
    {
        $avatarUrl = sprintf("%s/%s%s", $this->getUrl(), $this->baseCategoryAvatarPath, $category->getIconUrl());
        $category->setIconUrl($avatarUrl);
    }

    /**
     * @param Store $store
     */
    public function setAvatarStore(Store $store)
    {
        $avatarUrl = sprintf("%s/%s%s", $this->getUrl(), $this->baseStoreAvatarPath, $store->getAvatarUrl());
        $store->setAvatarUrl($avatarUrl);
    }

    public function getUrl()
    {
        return $this->request->getCurrentRequest()->getSchemeAndHttpHost();
    }

    /**
     * @param Store $store
     */
    public function setStorePhoto(Store $store)
    {
        /** @var RequestStack $request */
        $request = $this->request;
        $photoUrls = array_map(function (StorePhoto $storePhoto) {
            return sprintf("%s/%s%s", $this->getUrl(), $this->baseImagePath, $storePhoto->getPhoto()->getImageUrl());
        }, $store->getStorePhotos()->toArray());
        $store->setStorePhotoUrls($photoUrls);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCouponPhoto(Coupon $coupon)
    {
        $photoUrls = array_map(function (CouponPhoto $couponPhoto) {
            return sprintf("%s/%s%s", $this->getUrl(), $this->baseImagePath, $couponPhoto->getPhoto()->getImageUrl());
        }, $coupon->getCouponPhotos()->toArray());
        $coupon->setCouponPhotoUrls($photoUrls);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCouponUserPhoto(Coupon $coupon)
    {
        $photoUrls = array_map(function (CouponUserPhoto $couponUserPhoto) {
            return sprintf("%s/%s%s", $this->getUrl(), $this->baseImagePath, $couponUserPhoto->getPhoto()->getImageUrl());
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
        $similarCoupons = array_map(function (Coupon $coupon) use ($user) {
            $isLike = $this->likeListManager->isLike($user, $coupon);
            $isCanUse = $this->useListManager->isCanUse($user, $coupon);
            $type = $coupon->getType();
            $couponType = ['id' => $type, 'name' => $this->couponTypes[$type]];
            $this->setAvatarCoupon($coupon);
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
    public function setLink(Coupon &$coupon)
    {
        /** @var Router $router */
        $router = $this->router;
        /** @var RequestStack $request */
        $request = $this->request;
        $url = $request->getCurrentRequest()->getSchemeAndHttpHost();
        $link = $router->generate('admin_coupon_link', ['id' => $coupon->getId()]);
        $coupon->setLink(sprintf("%s%s", $url, $link));
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
     * @param SegementManager $segementManager
     * @return SerializeListener
     */
    public function setSegementManager($segementManager)
    {
        $this->segementManager = $segementManager;
        return $this;
    }

    /**
     * @param PushSettingManager $pushSettingManager
     * @return SerializeListener
     */
    public function setPushSettingManager($pushSettingManager)
    {
        $this->pushSettingManager = $pushSettingManager;
        return $this;
    }

    /**
     * @param MessageDeliveryManager $messageDeliveryManager
     * @return SerializeListener
     */
    public function setMessageDeliveryManager($messageDeliveryManager)
    {
        $this->messageDeliveryManager = $messageDeliveryManager;
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

    /**
     * @param mixed $router
     * @return SerializeListener
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @param mixed $request
     * @return SerializeListener
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $baseCouponAvatarPath
     * @return SerializeListener
     */
    public function setBaseCouponAvatarPath($baseCouponAvatarPath)
    {
        $this->baseCouponAvatarPath = $baseCouponAvatarPath;
        return $this;
    }

    /**
     * @param string $baseStoreAvatarPath
     * @return SerializeListener
     */
    public function setBaseStoreAvatarPath(string $baseStoreAvatarPath): SerializeListener
    {
        $this->baseStoreAvatarPath = $baseStoreAvatarPath;
        return $this;
    }

    /**
     * @param string $baseImagePath
     * @return SerializeListener
     */
    public function setBaseImagePath(string $baseImagePath): SerializeListener
    {
        $this->baseImagePath = $baseImagePath;
        return $this;
    }

    /**
     * @param string $baseCategoryAvatarPath
     * @return SerializeListener
     */
    public function setBaseCategoryAvatarPath(string $baseCategoryAvatarPath): SerializeListener
    {
        $this->baseCategoryAvatarPath = $baseCategoryAvatarPath;
        return $this;
    }
}
