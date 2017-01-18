<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\CategoryEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CategorySubscriber implements EventSubscriberInterface
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
            CategoryEvents::POST_CREATE => [
                ["clearCategories", 10],
                ["clearCategoriesIncludeShop", -10],
                ["clearCouponsDetail", -20],
                ["clearShopDetail", -30],
                ["clearShopByCategory", -40],
                ["clearFeaturedShopType", -50],
                ["clearFeaturedShop", -60],
                ["clearShopCouponByMap", -70],
            ],
        ];
    }

    /**
     * @param CategoryEvents $event
     */
    public function cleaCategories(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/categories")
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearCategoriesIncludeShop(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/categories/shop")
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearCouponsDetail(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/coupons/[0-9]+")
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearShopDetail(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/shops/[0-9]+")
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearShopByCategory(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/category/%d/shops", $event->getCategory()->getId()))
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearFeaturedShopType(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/featured/[0-9]+/shops/%d", $event->getCategory()->getId()))
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearFeaturedShop(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex("^/api/v1/featured/[0-9]+/shops")
            ->flush();
    }

    /**
     * @param CategoryEvents $event
     */
    public function clearShopCouponByMap(CategoryEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/map"))
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return CategorySubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
