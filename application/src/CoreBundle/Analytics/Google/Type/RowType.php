<?php

namespace CoreBundle\Analytics\Google\Type;

class RowType
{
    /**
     * @var RowColumnType
    */
    protected $rowColumn;

    /**
     * @param RowColumnType $rowColumn
     * @return RowType
     */
    public function setRowColumn(RowColumnType $rowColumn)
    {
        $this->rowColumn = $rowColumn;

        return $this;
    }

    /**
     * @return RowColumnType
     */
    public function getRowColumn()
    {
        return $this->rowColumn;
    }
}