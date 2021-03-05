<?php


namespace Classes\Request;


class AuthRequest extends  HttpRequest
{
    public string $nickname;
    public string $password;
}