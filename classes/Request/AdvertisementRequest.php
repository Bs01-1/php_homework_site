<?php


namespace Classes\Request;


class AdvertisementRequest extends HttpRequest
{
    public string $title;
    public string $address;
    public string $about;
    public string $type;
    public int $price;
}