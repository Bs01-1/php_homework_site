<?php


namespace Classes;



class Advertisement extends Model
{
    public int $id;
    public int $user_id;
    public string $title;
    public string $address;
    public string $about;
    public string $type;
    public int $price;
}