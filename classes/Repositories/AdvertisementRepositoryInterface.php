<?php


namespace Classes\Repositories;


use Classes\Collections\AdvertisementCollection;
use Classes\Request\AdvertisementRequest;
use Classes\Request\GetAdvertisementRequest;
use Classes\User;

interface AdvertisementRepositoryInterface
{
    public function createAdvertisement(AdvertisementRequest $advertisementRequest, User $user);

    public function getLastUserAdvertisement(User $user);

    public function getAdvertisementByLimitAndOffset(
        int $limit,
        int $offset,
        GetAdvertisementRequest $advertisementRequest
    ): ?AdvertisementCollection;
}