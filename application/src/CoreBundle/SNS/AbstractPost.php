<?php

namespace CoreBundle\SNS;

abstract class AbstractPost
{
    /**
     * @var string
    */
    private $prefix;

    /**
     * list Post
     *
     * @param \DateTime $from
     * @param \Datetime $to
     *
     * @return mixed
     */
    abstract public function listPost(\DateTime $from, \DateTime $to);

    public function getHashTags($message)
    {
        preg_match_all('/#([^\s]+)/', $message, $matches);
        $tags = [];
        if (count($matches[1]) > 0)
        {
            $tags = array_unique($matches[1]);
        }

        return $tags;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}
