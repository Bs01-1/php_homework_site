<?php


namespace Classes\Collections;


use Classes\Core\Collection;
use Classes\Info;

class InfoCollection extends Collection
{

    protected function isValidItem(object $item): bool
    {
        return $item instanceof Info;
    }
}