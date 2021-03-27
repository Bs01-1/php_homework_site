<?php


namespace Classes\Repositories;


use Classes\Buy;
use Classes\Request\CloseAdvertisement;

interface BuyRepositoryInterface
{
    public function createBuy(CloseAdvertisement $closeAdvertisement): bool;

    public function getBuy(int $advertisement_id, int $user_id): ?Buy;
}