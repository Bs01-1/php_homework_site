<?php


namespace Classes\Repositories;


use Classes\Info;

interface InfoRepositoryInterface
{
    public function getInfo(string $name): ?Info;
}