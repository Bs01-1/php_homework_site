<?php


namespace Classes\Repositories;

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
}