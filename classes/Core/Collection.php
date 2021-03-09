<?php


namespace Classes\Core;


use Exception;
use Traversable;

abstract class Collection implements \IteratorAggregate
{
    protected array $items = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function addItem(object $item)
    {
        if ($this->isValidItem($item)){
            $this->items[$this->getObjectHash($item)] = $item;
        }
    }

    protected function getObjectHash (object $item): string
    {
        return spl_object_hash($item);
    }

    abstract protected function isValidItem (object $item): bool;
}