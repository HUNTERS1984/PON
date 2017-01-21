<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\NewsEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsSubscriber implements EventSubscriberInterface
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
            NewsEvents::POST_CREATE => [
                ["clearNewsList", 10],
                ["clearNewsDetail", -10],
                ["clearGetProfile", -20],
            ],
            NewsEvents::POST_CATEGORY_CREATE => [
                ["clearNewsList", 10],
                ["clearNewsCategoryDetail", -10],
                ["clearGetProfile", -20],
            ],
        ];
    }

    /**
     * @param NewsEvents $event
     */
    public function clearNewsList(NewsEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/news')
            ->flush();
    }

    /**
     * @param NewsEvents $event
     */
    public function clearNewsDetail(NewsEvents $event)
    {
        $this->cacheManager
            ->invalidatePath(sprintf("/api/v1/news/%d", $event->getNews()->getId()))
            ->flush();
    }

    /**
     * @param NewsEvents $event
     */
    public function clearNewsCategoryDetail(NewsEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex(sprintf("^/api/v1/news/[0-9]+"))
            ->flush();
    }

    /**
     * @param NewsEvents $event
     */
    public function clearGetProfile(NewsEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/profile')
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return NewsSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
