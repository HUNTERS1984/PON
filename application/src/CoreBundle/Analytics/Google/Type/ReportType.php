<?php

namespace CoreBundle\Analytics\Google\Type;

use CoreBundle\Analytics\Type\BaseReportType;

class ReportType extends BaseReportType
{
    /**
     * @var array
    */
    protected $dataTable;

    public function __construct($item)
    {
        $this
            ->setUsers($item['users'])
            ->setAvgSessionDuration($item['avgSessionDuration'])
            ->setDataTable($item['dataTable'])
            ->setSessions($item['sessions'])
            ->setPageViews($item['pageviews'])
            ->setExitRate($item['exitRate'])
            ->setBounceRate($item['bounceRate']);
    }

    /**
     * @param array $dataTable
     * @return $this
     */
    public function setDataTable($dataTable)
    {
        $this->dataTable = $dataTable;

        return $this;
    }

    /**
     * @return array
     */
    public function getDataTable()
    {
        return $this->dataTable;
    }
}