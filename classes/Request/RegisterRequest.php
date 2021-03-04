<?php


namespace Classes\Request;


class RegisterRequest extends AuthRequest
{
    public string $city;
    public string $phone;
    public string $date;
    public string $sex;
}