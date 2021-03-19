<?php


namespace Classes\Services;


use Classes\Advertisement;
use Classes\Collections\AdvertisementCollection;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepositoryInterface;
use Classes\Request\AdvertisementRequest;
use Classes\Request\GetAdvertisementRequest;
use Classes\Request\MainAdvertisementRequest;
use Classes\User;

class AdvertisementService
{
    protected AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    public function createAdvertisement(AdvertisementRequest $advertisementRequest, User $user): bool
    {
        return $this->advertisementRepository->createAdvertisement($advertisementRequest, $user);
    }

    public function getLastUserAdvertisement(User $user): ?Advertisement
    {
        return $this->advertisementRepository->getLastUserAdvertisement($user);
    }

    public function getAdvertisements(
        Paginator $paginator,
        GetAdvertisementRequest $advertisementRequest
    ): ?AdvertisementCollection
    {
        return $this->advertisementRepository->getAdvertisementsByLimitAndOffsetAndType($paginator->getCount(), $paginator->getOffset(), $advertisementRequest);
    }

    public function getAdvertisementRatingById(int $id): ?Int
    {
        if ($advertisement = $this->advertisementRepository->getAdvertisementById($id)){
            return $advertisement->rating;
        }
        return null;
    }

    public function getCountAdvertisementsByType(string $type): Int
    {
        return $this->advertisementRepository->getCountAdvertisementsByType($type);
    }

    public function getCountAdvertisements(): Int
    {
        return $this->advertisementRepository->getCountAdvertisements();
    }

    public function getAdvertisementByLimitAndOrder(MainAdvertisementRequest $mainAdvertisementRequest): ?AdvertisementCollection
    {
        return $this->advertisementRepository->getAdvertisementsByLimitAndOrder($mainAdvertisementRequest);
    }
}