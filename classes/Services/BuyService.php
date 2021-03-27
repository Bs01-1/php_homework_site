<?php


namespace Classes\Services;


use Classes\Repositories\BuyRepositoryInterface;
use Classes\Request\CloseAdvertisement;

class BuyService
{
    protected BuyRepositoryInterface $buyRepository;

    public function __construct(BuyRepositoryInterface $buyRepository)
    {
        $this->buyRepository = $buyRepository;
    }

    public function addBuyRequest (CloseAdvertisement $closeAdvertisement): bool
    {
        return $this->buyRepository->createBuy($closeAdvertisement);
    }

    public function existBuyByAdvertisementIdAndUserId(int $advertisement_id, int $user_id): bool
    {
        return (bool) $this->buyRepository->getBuy($advertisement_id, $user_id);
    }
}