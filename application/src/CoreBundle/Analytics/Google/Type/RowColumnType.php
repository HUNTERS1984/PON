<?php

namespace CoreBundle\Analytics\Google\Type;

class RowColumnType
{
    /**
     * @var array
    */
    protected $columns;

    public function __construct()
    {
        $this->columns = [];
    }

    /**
     * @param array $columns
     * @return RowType
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param ColumnValueType $column
     * @return RowType
     */
    public function addValue($column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}