<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\News;
use Symfony\Component\EventDispatcher\Event;

class NewsEvents extends Event
{

    const PRE_CREATE = 'pon.event.news.pre_create';

    const POST_CREATE = 'pon.event.news.post_create';

    /**
     * @var News
     */
    protected $news;

    /**
     * @param News $news
     * @return NewsEvents
     */
    public function setNews(News $news): NewsEvents
    {
        $this->news = $news;

        return $this;
    }

    /**
     * @return News
     */
    public function getNews(): News
    {
        return $this->news;
    }
}
