<?php


namespace Classes\Core;


class Paginator
{
    protected int $page;
    protected int $count = 10;

    public function __construct(int $page)
    {
        $this->page = $page;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getOffset(): int
    {
        return $this->page * $this->count;
    }

}