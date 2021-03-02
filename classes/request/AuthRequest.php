<?php


namespace Classes\Request;


class AuthRequest extends HttpRequest
{
    public string $nick;
    public string $password;
    public string $city;
    public string $phone;
    public string $date;
    public string $sex;
}