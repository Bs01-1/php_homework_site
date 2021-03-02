<?php

namespace Classes;

class Connect
{
    public static function getConnect () {
        return new \mysqli('localhost', 'root', 'root', 'realty');
    }
}