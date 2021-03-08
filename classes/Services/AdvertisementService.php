<?php


namespace Classes\Services;


use Classes\Advertisement;
use Classes\Collections\AdvertisementCollection;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepositoryInterface;
use Classes\Request\AdvertisementRequest;
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

    public function getAdvertisements(Paginator $paginator): AdvertisementCollection
    {
        return $this->advertisementRepository->getAdvertisementByLimitAndOffset($paginator->getCount(), $paginator->getOffset());
    }
}