<?php

namespace Classes;

use Classes\Repositories\UserRepository;

class User
{
    public int $id;
    public string $nickname;
    public string $password;
    public string $city;
    public string $phone;
    public string $date;
    public string $sex;

    public function __construct($userRepository)
    {
        $this->id = $userRepository['id'];
        $this->nickname = $userRepository['nickname'];
        $this->password = $userRepository['password'];
        $this->city = $userRepository['city'];
        $this->phone = $userRepository['phone'];
        $this->date = $userRepository['date'];
        $this->sex = $userRepository['gender'];
    }
}