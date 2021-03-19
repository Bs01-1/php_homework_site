<?php


namespace Classes\Request;


class MainAdvertisementRequest extends HttpRequest
{
    public string $name;
    public string $orderBy;
    public bool $desc;
}