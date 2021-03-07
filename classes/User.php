<?php

namespace Classes;

use Classes\Repositories\UserRepository;

class User extends Model
{
    public int $id;
    public string $nickname;
    public string $password;
    public string $city;
    public string $phone;
    public string $date;
    public string $sex;
    public string $token;

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