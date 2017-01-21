<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\UseListEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UseListSubscriber implements EventSubscriberInterface
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
            UseListEvents::POST_CREATE => [
                ["clearFeaturedCoupon", 10],
                ["clearCouponsByFeaturedAndCategory", -10],
                ["clearCouponsDetail", -20],
                ["clearShopDetail", -30],
                ["clearShopCouponByMap", -40],
                ["clearSearchCoupon", -50],
                ["clearFavoriteCoupon", -60],
                ["clearUsedCoupon", -70],
                ["clearRequestCoupon", -80],
                ["clearRequestCouponDetail", -90],
                ["clearGetProfile", -100],

            ],
        ];
    }

    /**
     * @param UseListEvents $event
     */
    public function clearFeaturedCoupon(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/featured/[0-9]+/coupons')
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearCouponsByFeaturedAndCategory(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/featured/[0-9]+/category/[0-9]+/coupons')
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearCouponsDetail(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/coupons/%d", $event->getUseList()->getCoupon()->getId()))
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearShopDetail(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/shops/%d", $event->getUseList()->getCoupon()->getStore()->getId()))
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearShopCouponByMap(UseListEvents $event)
    {
        $store = $event->getUseList()->getCoupon()->getStore();
        $lat = $store->getLatitude();
        $long = $store->getLongitude();
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map/%s/%s/shops", $lat, $long))
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearSearchCoupon(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/search/coupons")
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearFavoriteCoupon(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/favorite/coupons')
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearUsedCoupon(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/used/coupons')
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearRequestCoupon(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/request/coupons')
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearRequestCouponDetail(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/request/coupons/[a-zA-Z0-9]+')
            ->flush();
    }

    /**
     * @param UseListEvents $event
     */
    public function clearGetProfile(UseListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/profile')
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return UseListSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
