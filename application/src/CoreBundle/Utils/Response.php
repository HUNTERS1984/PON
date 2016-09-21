<?php
namespace CoreBundle\Utils;


class Response
{
    public static function getData($data)
    {
       return [
            "code"      =>  1000,
            "message"   =>  'OK',
            "data"      =>  $data
        ];
    }
}