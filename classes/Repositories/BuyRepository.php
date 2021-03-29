<?php


namespace Classes\Repositories;


use Classes\Buy;
use Classes\Collections\BuyCollection;
use Classes\Request\CloseAdvertisement;

class BuyRepository extends Repository implements BuyRepositoryInterface
{

    public function createBuy(CloseAdvertisement $closeAdvertisement): bool
    {
        $result = $this->connection->query("INSERT INTO buy (advertisement_id, user_id) 
            VALUES ({$closeAdvertisement->advertisement_id}, {$closeAdvertisement->user_id})");
        return (bool) $result;
    }

    public function getBuyByAdvertisementIdAndUserId(int $advertisement_id, int $user_id): ?Buy
    {
        $result = $this->connection->query("SELECT * FROM buy WHERE advertisement_id = {$advertisement_id} AND user_id = {$user_id}");
        if (!$result || $result->num_rows < 1){
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();

        return Buy::createFromArray($resultArray);
    }

    public function getBuysByUserIdAndSuccess(int $user_id, bool $success): ?BuyCollection
    {
        $success = ($success) ? 1 : 0;
        $result = $this->connection->query("SELECT * FROM buy WHERE user_id = {$user_id} AND success = {$success}");

        $buyCollection = new BuyCollection();
        if (!$result->num_rows){
            return null;
        }
        while ($buyArray = $result->fetch_assoc()) {
            $buyCollection->addItem(Buy::createFromArray($buyArray));
        }
        return $buyCollection;
    }

    public function getBuysByAdvertisementId(int $advertisement_id): ?BuyCollection
    {
        $result = $this->connection->query("SELECT * FROM buy WHERE advertisement_id = {$advertisement_id}");

        $buyCollection = new BuyCollection();
        if (!$result->num_rows) {
            return null;
        }
        while ($buyArray = $result->fetch_assoc()) {
            $buyCollection->addItem(Buy::createFromArray($buyArray));
        }
        return $buyCollection;
    }

    public function deleteBuy(Buy $buy): bool
    {
        return $this->connection->query("DELETE FROM buy WHERE id = {$buy->id}");
    }

    public function updateSuccess(Buy $buy, bool $success): bool
    {
        $success = ($success) ? 1 : 0;
        return (bool) $this->connection->query("UPDATE buy SET success = {$success} WHERE id = {$buy->id}");
    }

    public function getBuyById(int $id): ?Buy
    {
        $result = $this->connection->query("SELECT * FROM buy WHERE id = {$id}");
        if (!$result->num_rows) {
            return null;
        }
        $resultArray = $result->fetch_assoc();
        $result->free_result();
        return Buy::createFromArray($resultArray);
    }

    public function deleteBuysByAdvertisementId(int $advertisement_id): bool
    {
        return $this->connection->query("DELETE FROM buy WHERE advertisement_id = {$advertisement_id}");
    }
}