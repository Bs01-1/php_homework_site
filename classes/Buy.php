<?php


namespace Classes;


class Buy extends Model
{
    public int $id;
    public int $advertisement_id;
    public int $user_id;
    public bool $success;
}