<?php

namespace CoreBundle\Analytics\Google;


use CoreBundle\Analytics\Google\Type\ColumnValueType;
use CoreBundle\Analytics\Google\Type\ReportType;
use CoreBundle\Analytics\Google\Type\ColumnType;
use CoreBundle\Analytics\Google\Type\DataTableType;
use CoreBundle\Analytics\Google\Type\RowColumnType;
use CoreBundle\Analytics\Google\Type\RowType;
use CoreBundle\Analytics\AbstractReport;
use CoreBundle\Analytics\Google\Request\ReportRequest;

class Report extends AbstractReport
{
    /**
     * @var ReportRequest
     */
    protected $request;

    /**
     * Construct
     *
     * @param \Google_Service_Analytics $client
     */
    public function __construct(\Google_Service_Analytics $client)
    {
        $this->initialize($client);
    }

    /**
     * Initialize the Report
     *
     * @param \Google_Service_Analytics $client
     */
    public function initialize(\Google_Service_Analytics $client)
    {
        $this->request = new ReportRequest($client);
    }

    /**
     * List Report
     *
     * @param string $dimensions
     * @param string $metrics
     * @return mixed
     */
    public function listReport($dimensions, $metrics)
    {
        $this->request
            ->setMetrics($metrics)
            ->setDimensions($dimensions);
        $response = $this->request->listReport();
        $result = $response->getResult();
        $totalAllResults = $result->getTotalsForAllResults();
        /** @var \Google_Service_Analytics_GaDataDataTable $dataTable */
        $dataTable = $result->getDataTable();
        $tableType = new DataTableType();
        foreach ($dataTable->getRows() as $item) {
            /** @var \Google_Service_Analytics_GaDataDataTableRows $row */
            $row = $item;
            $rowType = new RowType();
            $rowColumnType = new RowColumnType();
            foreach ($row->getC() as $itemRowCol) {
                /** @var \Google_Service_Analytics_GaDataDataTableRowsC $rowCol*/
                $rowCol = $itemRowCol;
                $columnValueType = new ColumnValueType();
                $columnValueType->setValue($rowCol->getV());
                $rowColumnType->addValue($columnValueType);
            }
            $rowType->setRowColumn($rowColumnType);
            $tableType->addRow($rowType);
        }

        foreach ($dataTable->getCols() as $item) {
            /** @var \Google_Service_Analytics_GaDataDataTableCols $col */
            $col = $item;
            $colType = new ColumnType();
            $colType
                ->setType($col->getType())
                ->setLabel($col->getLabel())
                ->setId($col->getId());
            $tableType->addColumn($colType);
        }

        $item['dataTable'] = $tableType;
        $item['sessions'] = $totalAllResults['ga:sessions'];
        $item['users'] = $totalAllResults['ga:users'];
        $item['pageviews'] = $totalAllResults['ga:pageviews'];
        $item['avgSessionDuration'] = $totalAllResults['ga:avgSessionDuration'];
        $item['bounceRate'] = $totalAllResults['ga:bounceRate'];
        $item['exitRate'] = $totalAllResults['ga:exitRate'];

        return new ReportType($item);
    }
}