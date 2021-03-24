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
    public string $gender;
    public string $token;
    public string $createdAt;
}