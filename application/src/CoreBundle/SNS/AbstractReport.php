<?php

namespace CoreBundle\SNS;

abstract class AbstractReport
{
    /**
     * List Report
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return mixed
     */
    abstract public function listReport($from, $to);

}
