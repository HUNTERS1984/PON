<?php

namespace CoreBundle\SNS\Facebook\Type;

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

    public function __construct($params, $prefix = '')
    {
        $images = [];
        if (isset($params['attachments']['data'])) {
            $data = $params['attachments']['data'];
            if (isset($data[0]['subattachments']['data'])) {
                $data = $data[0]['subattachments']['data'];
            }

            $images = array_map(
                function ($attachment) {
                    if ($attachment['type'] == 'photo') {
                        return $attachment['media']['image']['src'];
                    }

                    return '';
                },
                $data
            );
        }
        $this
            ->setId(sprintf("%s%s", $prefix, $params['id']))
            ->setCreatedAt(new \DateTime($params['created_time']))
            ->setPublished((bool)$params['is_published'])
            ->setPrivacy($params['privacy']['value'])
            ->setImages($images)
            ->setMessage($params['message'])
            ->setUrl($params['permalink_url']);
    }
}