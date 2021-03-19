<?php


namespace Classes\Request;


class GetAdvertisementRequest extends HttpRequest
{
    public int $page;
    public string $type;
    public string $sortBy;
    public int $sortDesc;
}