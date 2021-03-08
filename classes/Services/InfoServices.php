<?php


namespace Classes\Services;


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
}