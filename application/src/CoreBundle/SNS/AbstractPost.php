<?php

namespace CoreBundle\SNS;

abstract class AbstractPost
{
    /**
     * list Post
     *
     * @param \DateTime $from
     * @param \Datetime $to
     *
     * @return mixed
     */
    abstract public function listPost(\DateTime $from, \DateTime $to);
}
