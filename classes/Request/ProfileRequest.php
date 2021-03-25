<?php


namespace Classes\Request;


class ProfileRequest extends HttpRequest
{
    public string $city;
    public string $phone;
    public string $password;
    public string $newPassword;
    public string $newPasswordRepeat;
    public int $id;
}