<?php


namespace Classes;


class Model
{
    public static function createFromArray(array $params): self
    {
        $model = new self();
        foreach ($params as $key => $param){

            if (property_exists($model, $key)){
                $model->$key = $param;
            }
        }
        return $model;
    }
}