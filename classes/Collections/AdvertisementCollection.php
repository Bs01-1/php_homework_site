<?php


namespace Classes\Collections;


use Classes\Advertisement;
use Classes\Core\Collection;

class AdvertisementCollection extends Collection
{
    protected function isValidItem(object $item): bool
    {
        return $item instanceof Advertisement;
    }
}