<?php

namespace CoreBundle\Analytics;

abstract class AbstractReport
{
    /**
     * List Report
     *
     * @param string $dimensions
     * @param string $metrics
     * @return mixed
     */
    abstract public function listReport($dimensions, $metrics);
}
