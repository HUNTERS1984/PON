<?php

namespace CoreBundle\Analytics;

abstract class AbstractDriver
{
    /**
     * List Report
     *
     * @param string $dimensions
     * @param  string $metrics
     * @return mixed
     */
    abstract public function listReport($dimensions, $metrics);
}
