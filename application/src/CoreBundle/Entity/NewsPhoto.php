<?php

namespace CoreBundle\Entity;

/**
 * NewsPhoto
 */
class NewsPhoto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoreBundle\Entity\News
     */
    private $news;

    /**
     * @var \CoreBundle\Entity\Photo
     */
    private $photo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set news
     *
     * @param \CoreBundle\Entity\News $news
     *
     * @return NewsPhoto
     */
    public function setNews(\CoreBundle\Entity\News $news = null)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return \CoreBundle\Entity\News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set photo
     *
     * @param \CoreBundle\Entity\Photo $photo
     *
     * @return NewsPhoto
     */
    public function setPhoto(\CoreBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \CoreBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
