<?php


namespace Classes\Collections;


use Classes\Buy;
use Classes\Core\Collection;

class BuyCollection extends Collection
{
        protected function isValidItem(object $item): bool
    {
        return $item instanceof Buy;
    }

}