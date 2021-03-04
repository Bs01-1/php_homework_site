<?php


namespace Classes\Request;


class AuthRequest extends  HttpRequest
{
    public string $nick;
    public string $password;
}