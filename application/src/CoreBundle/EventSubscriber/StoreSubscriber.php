<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\StoreEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StoreSubscriber implements EventSubscriberInterface
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
            StoreEvents::POST_CREATE => [
                ["clearCouponsDetail", 10],
                ["clearShopDetail", -10],
                ["clearShopByCategory", -20],
                ["clearFeaturedShopType", -30],
                ["clearFeaturedShop", -40],
                ["clearShopCouponByMap", -50],
                ["clearCategoriesIncludeShop", -60]
            ],
        ];
    }

    /**
     * @param StoreEvents $event
     */
    public function clearCouponsDetail(StoreEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/coupons/[0-9]+")
            ->flush();
    }

    /**
     * @param StoreEvents $event
     */
    public function clearShopDetail(StoreEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/shops/%d", $event->getStore()->getId()))
            ->flush();
    }

    /**
     * @param StoreEvents $event
     */
    public function clearShopByCategory(StoreEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/category/%d/shops", $event->getStore()->getCategory()->getId()))
            ->flush();
    }

    /**
     * @param StoreEvents $event
     */
    public function clearFeaturedShopType(StoreEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/featured/[0-9]+/shops/%d", $event->getStore()->getCategory()->getId()))
            ->flush();
    }

    /**
     * @param StoreEvents $event
     */
    public function clearFeaturedShop(StoreEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/featured/[0-9]+/shops")
            ->flush();
    }

    /**
     * @param StoreEvents $event
     */
    public function clearShopCouponByMap(StoreEvents $event)
    {
        $store = $event->getStore();
        $lat = $store->getLatitude();
        $long = $store->getLongitude();
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map/%s/%s/shops", $lat, $long))
            ->flush();
    }

    /**
     * @param StoreEvents $event
     */
    public function clearCategoriesIncludeShop(StoreEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/categories/shop")
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return StoreSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
