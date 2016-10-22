<?php

namespace CoreBundle\Utils;

class Data
{
    public function setData($items, $object)
    {
        foreach($items as $key=> $item) {
            $field = strtoupper($key);
            $function = 'set'.$field;
            if(!method_exists($object, $function)) {
                continue;
            }
            $object->$function($item);
        }

        return $object;
    }
}