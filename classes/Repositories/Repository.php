<?php

namespace Classes\Repositories;

class Repository
{
    protected \mysqli $connection;

    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
    }
}