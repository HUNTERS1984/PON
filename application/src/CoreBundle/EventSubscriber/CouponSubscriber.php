<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\CouponEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CouponSubscriber implements EventSubscriberInterface
{
    /**
     * @var CacheManager $cacheManager
     */
    protected $cacheManager;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            CouponEvents::POST_CREATE => [
                ["clearFeaturedCoupon", 10],
                ["clearCouponsByFeaturedAndCategory", -10],
                ["clearCouponsDetail", -20],
                ["clearShopDetail", -30],
                ["clearShopCouponByMap", -40],
                ["clearSearchCouponByMap", -50],
            ],
        ];
    }

    /**
     * @param CouponEvents $event
     */
    public function clearFeaturedCoupon(CouponEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/featured/[0-9]+/coupons')
            ->flush();
    }

    /**
     * @param CouponEvents $event
     */
    public function clearCouponsByFeaturedAndCategory(CouponEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/featured/[0-9]+/category/[0-9]+/coupons')
            ->flush();
    }

    /**
     * @param CouponEvents $event
     */
    public function clearCouponsDetail(CouponEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/coupons/%d", $event->getCoupon()->getId()))
            ->flush();
    }

    /**
     * @param CouponEvents $event
     */
    public function clearShopDetail(CouponEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/shops/%d", $event->getCoupon()->getStore()->getId()))
            ->flush();
    }

    /**
     * @param CouponEvents $event
     */
    public function clearShopCouponByMap(CouponEvents $event)
    {
        $store = $event->getCoupon()->getStore();
        $lat = $store->getLatitude();
        $long = $store->getLongitude();
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map/%s/%s/shops", $lat, $long))
            ->flush();
    }

    /**
     * @param CouponEvents $event
     */
    public function clearSearchCouponByMap(CouponEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/search/coupons")
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return CouponSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
