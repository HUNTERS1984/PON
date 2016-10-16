<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\StoreManager;
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
     * @param Coupon $coupon
     */
    public function preCouponSerialize(Coupon $coupon)
    {
        $this->setLike($coupon);
        $this->setCouponType($coupon);
        $this->setCanUse($coupon);
        $this->setCouponPhoto($coupon);
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
    public function setCanUse(Coupon $coupon)
    {
        if(!$coupon->isNeedLogin()) {
            $coupon->setCanUse(true);
            return;
        }

        if (!$user = $this->getUser()) {
            $coupon->setCanUse(false);
            return;
        }

        $isCanUse = $this->couponManager->isCanUse($user, $coupon);
        $coupon->setCanUse($isCanUse);
    }

    /**
     * @param Coupon $coupon
     */
    public function setCouponType(Coupon $coupon)
    {
        $type = $coupon->getType();
        $coupon->setCouponType(['id' => $type, 'name' => $this->couponTypes[$type]]);
    }

    /**
     * @param Coupon $coupon
     */
    public function setLike(Coupon $coupon)
    {
        if (!$user = $this->getUser()) {
            $coupon->setLike(false);
            return;
        }

        $isLike = $this->couponManager->isLike($user, $coupon);
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

        $isFollow = $this->storeManager->isFollow($user, $store);
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
}
