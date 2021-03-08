<?php

namespace Classes;


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
}