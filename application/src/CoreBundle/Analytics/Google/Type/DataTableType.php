<?php

namespace CoreBundle\Analytics\Google\Type;

class DataTableType
{
    /**
     * @var array
    */
    protected $rows;

    /**
     * @var array
    */
    protected $columns;

    public function __construct()
    {
        $this->rows = [];
        $this->columns = [];
    }

    /**
     * @param array $rows
     * @return DataTableType
     */
    public function setRows(array $rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @param RowType $row
     * @return DataTableType
     */
    public function addRow(RowType $row)
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param array $columns
     * @return DataTableType
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param ColumnType $column
     * @return DataTableType
     */
    public function addColumn(ColumnType $column)
    {
        $this->columns[] = $column;

        return $this;
    }
}