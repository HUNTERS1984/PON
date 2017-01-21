<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\FollowListEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FollowListSubscriber implements EventSubscriberInterface
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
            FollowListEvents::POST_CREATE => [
                ["clearCouponsDetail", 10],
                ["clearShopDetail", -10],
                ["clearShopByCategory", -20],
                ["clearFeaturedShopType", -30],
                ["clearFeaturedShop", -40],
                ["clearShopCouponByMap", -50],
                ["clearCategoriesIncludeShop", -60],
                ["clearFavoriteCoupon", -70],
                ["clearUsedCoupon", -80],
                ["clearSearchCoupon", -90],
                ["clearFollowShop", -100],
                ["clearGetProfile", -100],

            ],
            FollowListEvents::POST_DELETE => [
                ["clearCouponsDetail", 10],
                ["clearShopDetailDelete", -10],
                ["clearShopByCategoryDelete", -20],
                ["clearFeaturedShopTypeDelete", -30],
                ["clearFeaturedShop", -40],
                ["clearShopCouponByMapDelete", -50],
                ["clearCategoriesIncludeShop", -60],
                ["clearFavoriteCoupon", -70],
                ["clearUsedCoupon", -80],
                ["clearSearchCoupon", -90],
                ["clearFollowShop", -100],
                ["clearGetProfile", -100],
            ],
        ];
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearCouponsDetail(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/coupons/[0-9]+")
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearShopDetail(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/shops/%d", $event->getFollowList()->getStore()->getId()))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearShopDetailDelete(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("/api/v1/shops/[0-9]+"))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearShopByCategory(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/category/%d/shops", $event->getFollowList()->getStore()->getCategory()->getId()))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearShopByCategoryDelete(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/category/[0-9]+/shops"))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearFeaturedShopType(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/featured/[0-9]+/shops/%d", $event->getFollowList()->getStore()->getCategory()->getId()))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearFeaturedShopTypeDelete(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/featured/[0-9]+/shops/[0-9]+"))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearFeaturedShop(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/featured/[0-9]+/shops")
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearShopCouponByMap(FollowListEvents $event)
    {
        $store = $event->getFollowList()->getStore();
        $lat = $store->getLatitude();
        $long = $store->getLongitude();
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map/%s/%s/shops", $lat, $long))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearShopCouponByMapDelete(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map"))
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearCategoriesIncludeShop(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/categories/shop")
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearFavoriteCoupon(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/favorite/coupons')
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearUsedCoupon(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/used/coupons')
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearSearchCoupon(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/search/coupons")
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearFollowShop(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/follow/shops")
            ->flush();
    }

    /**
     * @param FollowListEvents $event
     */
    public function clearGetProfile(FollowListEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/profile')
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return FollowListSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
