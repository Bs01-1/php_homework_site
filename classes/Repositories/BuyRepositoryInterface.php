<?php


namespace Classes\Repositories;


use Classes\Buy;
use Classes\Collections\BuyCollection;
use Classes\Request\CloseAdvertisement;

interface BuyRepositoryInterface
{
    public function createBuy(CloseAdvertisement $closeAdvertisement): bool;

    public function getBuyByAdvertisementIdAndUserId(int $advertisement_id, int $user_id): ?Buy;

    public function getBuyById(int $id): ?Buy;

    public function getBuysByUserIdAndSuccess(int $user_id, bool $success): ?BuyCollection;

    public function getBuysByAdvertisementId(int $advertisement_id): ?BuyCollection;

    public function updateSuccess(Buy $buy, bool $success): bool;

    public function deleteBuy(Buy $buy): bool;

    public function deleteBuysByAdvertisementId(int $advertisement_id): bool;
}