<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\LikeListEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LikeListSubscriber implements EventSubscriberInterface
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
            LikeListEvents::POST_CREATE => [
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
            ],
        ];
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearFeaturedCoupon(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/featured/[0-9]+/coupons')
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearCouponsByFeaturedAndCategory(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/featured/[0-9]+/category/[0-9]+/coupons')
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearCouponsDetail(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/coupons/%d", $event->getLikeList()->getCoupon()->getId()))
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearShopDetail(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/shops/%d", $event->getLikeList()->getCoupon()->getStore()->getId()))
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearShopCouponByMap(LikeListEvents $event)
    {
        $store = $event->getLikeList()->getCoupon()->getStore();
        $lat = $store->getLatitude();
        $long = $store->getLongitude();
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map/%s/%s/shops", $lat, $long))
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearSearchCoupon(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/search/coupons")
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearFavoriteCoupon(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/favorite/coupons')
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearUsedCoupon(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/used/coupons')
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearRequestCoupon(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/request/coupons')
            ->flush();
    }

    /**
     * @param LikeListEvents $event
     */
    public function clearRequestCouponDetail(LikeListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/request/coupons/[a-zA-Z0-9]+')
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return LikeListSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
