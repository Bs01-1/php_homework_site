<?php


namespace Classes\Repositories;

use Classes\Collections\InfoCollection;
use Classes\Info;

class InfoRepository extends Repository implements InfoRepositoryInterface
{

    public function getInfo(string $name): ?Info
    {
        $result = $this->connection->query("SELECT * FROM info WHERE name = '{$name}'");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();
        return Info::createFromArray($resultArray);
    }

    public function getAll(): InfoCollection
    {
        $result = $this->connection->query("SELECT * FROM info");

        $infoCollection = new InfoCollection();
        while ($resultArray = $result->fetch_assoc()){
                $infoCollection->addItem(Info::createFromArray($resultArray));
        }
        $result->free_result();

        return $infoCollection;
    }
}