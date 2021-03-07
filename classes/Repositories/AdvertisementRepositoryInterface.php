<?php


namespace Classes\Repositories;


use Classes\Request\AdvertisementRequest;
use Classes\User;

interface AdvertisementRepositoryInterface
{
    public function createAdvertisement(AdvertisementRequest $advertisementRequest, User $user);

    public function getLastUserAdvertisement(User $user);
}