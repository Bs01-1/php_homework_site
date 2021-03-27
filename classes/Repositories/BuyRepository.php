<?php


namespace Classes\Repositories;


use Classes\Buy;
use Classes\Request\CloseAdvertisement;

class BuyRepository extends Repository implements BuyRepositoryInterface
{

    public function createBuy(CloseAdvertisement $closeAdvertisement): bool
    {
        $result = $this->connection->query("INSERT INTO buy (advertisement_id, user_id) 
            VALUES ({$closeAdvertisement->advertisement_id}, {$closeAdvertisement->user_id})");
        return (bool) $result;
    }

    public function getBuy(int $advertisement_id, int $user_id): ?Buy
    {
        $result = $this->connection->query("SELECT * FROM buy WHERE advertisement_id = {$advertisement_id} AND user_id = {$user_id}");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();

        return Buy::createFromArray($resultArray);
    }
}