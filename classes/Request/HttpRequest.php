<?php


namespace Classes\Request;


class HttpRequest
{
    public function __construct(array $params)
    {
        foreach ($params as $key => $param){

            if (property_exists($this, $key)){
                $this->$key = $param;
            }
        }
    }
}