<?php

namespace CoreBundle\EventSubscriber;

use CoreBundle\Event\AppUserEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppUserSubscriber implements EventSubscriberInterface
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
            AppUserEvents::POST_CREATE => [
                ["clearGetProfile", 10],
            ],
        ];
    }

    /**
     * @param AppUserEvents $event
     */
    public function clearGetProfile(AppUserEvents $event)
    {
        $this->cacheManager
            ->invalidateRegex('^/api/v1/profile')
            ->flush();
    }

    /**
     * @param CacheManager $cacheManager
     * @return AppUserSubscriber
     */
    public function setCacheManager($cacheManager)
    {
        $this->cacheManager = $cacheManager;

        return $this;
    }
}
