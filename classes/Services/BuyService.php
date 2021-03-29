<?php


namespace Classes\Services;


use Classes\Advertisement;
use Classes\Buy;
use Classes\Collections\BuyCollection;
use Classes\Repositories\AdvertisementRepositoryInterface;
use Classes\Repositories\BuyRepositoryInterface;
use Classes\Request\CloseAdvertisement;
use Classes\User;

class BuyService
{
    protected BuyRepositoryInterface $buyRepository;
    protected AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(BuyRepositoryInterface $buyRepository, AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->buyRepository = $buyRepository;
        $this->advertisementRepository = $advertisementRepository;
    }

    public function addBuyRequest (CloseAdvertisement $closeAdvertisement): bool
    {
        return $this->buyRepository->createBuy($closeAdvertisement);
    }

    public function existBuyByAdvertisementIdAndUserId(int $advertisement_id, int $user_id): bool
    {
        return (bool) $this->buyRepository->getBuyByAdvertisementIdAndUserId($advertisement_id, $user_id);
    }

    public function getBuysByUserAndSuccess(User $user, bool $success): ?BuyCollection
    {
        return $this->buyRepository->getBuysByUserIdAndSuccess($user->id, $success);
    }

    public function getBuysByUserWhereSuccessFalse(User $user): ?BuyCollection
    {
        if (!$advertisementCollection = $this->advertisementRepository->getAdvertisementsByUser($user)) {
            return null;
        }
        $buyCollection = new BuyCollection();
        /**
         * @var $item Advertisement
         */
        foreach ($advertisementCollection as $advertisement) {
            if ($buys = $this->buyRepository->getBuysByAdvertisementId($advertisement->id)) {
                foreach ($buys as $buy) {
                    if (!$buy->success) {
                        $buyCollection->addItem($buy);
                    }
                }
            }
        }
        return $buyCollection->isEmpty() ? null : $buyCollection;
    }

    public function getBuyById(int $id): ?Buy
    {
        return $this->buyRepository->getBuyById($id);
    }

    public function deleteBuy(Buy $buy): bool
    {
        return $this->buyRepository->deleteBuy($buy);
    }

    public function saleAdvertisement(Buy $buy, Advertisement $advertisement): bool
    {
        if ($this->buyRepository->updateSuccess($buy, true)) {
            if ($this->advertisementRepository->updateRelevanceById($advertisement->id, 'close')) {
                $this->buyRepository->deleteBuysByAdvertisementId($advertisement->id);
                return true;
            }
            return false;
        }
        return false;
    }
}