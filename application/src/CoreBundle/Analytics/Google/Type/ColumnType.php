<?php

namespace CoreBundle\Analytics\Google\Type;

class ColumnType
{
    /**
     * @var string
    */
    protected $id;

    /**
     * @var string
    */
    protected $label;

    /**
     * @var string
    */
    protected $type;

    /**
     * @param string $id
     * @return ColumnType
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $label
     * @return ColumnType
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $type
     * @return ColumnType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}