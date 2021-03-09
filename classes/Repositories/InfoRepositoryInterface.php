<?php


namespace Classes\Repositories;


use Classes\Collections\InfoCollection;
use Classes\Info;

interface InfoRepositoryInterface
{
    public function getInfo(string $name): ?Info;

    public function getAll(): InfoCollection;
}