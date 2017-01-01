<?php

namespace CoreBundle\SNS\Twitter\Type;

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
        $url = sprintf("%s/%s","https://twitter.com/statuses",$item->id);
        if(isset($item->entities->media)) {
            $images = array_map(function($media){
                if($media->type == 'photo') {
                    return $media->media_url;
                }
                return '';
            }, $item->entities->media);
        }


        $this
            ->setId(sprintf("%s%s",$prefix,$item->id))
            ->setCreatedAt(new \DateTime($item->created_at))
            ->setPublished(true)
            ->setPrivacy('EVERYONE')
            ->setImages($images)
            ->setMessage($item->text)
            ->setUrl($url);
    }
}