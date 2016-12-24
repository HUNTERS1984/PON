<?php

namespace CoreBundle\Analytics\Google\Type;

class ColumnValueType
{
    /**
     * @var string
    */
    protected $value;

    /**
     * @param string $value
     * @return ColumnValueType
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}