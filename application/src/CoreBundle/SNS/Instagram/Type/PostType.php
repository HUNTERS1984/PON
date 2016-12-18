<?php

namespace CoreBundle\SNS\Instagram\Type;

use CoreBundle\SNS\Type\BasePostType;

class PostType extends BasePostType
{
    /**
     * @var boolean
    */
    private $published;

    /**
     * @var string
    */
    private $privacy;

    /**
     * @param boolean $published
     * @return PostType
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param string $privacy
     * @return PostType
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    public function __construct($item, $prefix='')
    {
        $images = [];
        if(isset($item->images->standard_resolution->url)) {
            $images[] = $item->images->standard_resolution->url;
        }
        $date = new \DateTime();
        $date->setTimestamp($item->created_time);

        $this
            ->setId(sprintf("%s%s",$prefix,$item->id))
            ->setCreatedAt($date)
            ->setPublished(true)
            ->setPrivacy('EVERYONE')
            ->setImages($images)
            ->setMessage($item->caption->text)
            ->setUrl($item->link);
    }
}