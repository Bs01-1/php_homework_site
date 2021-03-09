<?php


namespace Classes\Services;


use Classes\Collections\InfoCollection;
use Classes\Info;
use Classes\Repositories\InfoRepositoryInterface;

class InfoServices
{
    protected InfoRepositoryInterface $infoRepository;

    public function __construct(InfoRepositoryInterface $infoRepository)
    {
        $this->infoRepository = $infoRepository;
    }

    public function getInfo(string $name): ?Info
    {
        return $this->infoRepository->getInfo($name);
    }

    public function getAll(): InfoCollection
    {
        return $this->infoRepository->getAll();
    }
}