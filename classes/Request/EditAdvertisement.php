<?php


namespace Classes\Request;


class EditAdvertisement extends HttpRequest
{
    public int $advertisement_id;
    public string $title;
    public string $address;
    public string $about;
    public int $price;
}