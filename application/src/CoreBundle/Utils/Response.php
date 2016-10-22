<?php
namespace CoreBundle\Utils;


class Response
{
    public static function getData($data = [], $pagination = [])
    {
        $result = [
            "code"      =>  1000,
            "message"   =>  'OK',
            "data"      =>  $data
        ];
        if(!empty($pagination)) {
            $result['pagination'] = $pagination;
        }
       return $result;
    }
}