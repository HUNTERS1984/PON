<?php

namespace CoreBundle\SNS\Type;

class BasePostType
{
    /**
     * @var string
    */
    private $id;

    /**
     * @var string
    */
    private $message;

    /**
     * @var \DateTime
    */
    private $createdAt;

    /**
     * @var string
    */
    private $url;

    /**
     * @var array
     */
    private $hashTags;

    /**
     * @var array
    */
    private $images;

    public function __construct()
    {
        $this->images = [];
        $this->hashTags = [];
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param array $images
     * @return $this
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function addImage($image)
    {
        $this->images[] = $image;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param array $hashTags
     * @return $this
     */
    public function setHashTags($hashTags)
    {
        $this->hashTags = $hashTags;

        return $this;
    }

    /**
     * @return array
     */
    public function getHashTags()
    {
        return $this->hashTags;
    }
}