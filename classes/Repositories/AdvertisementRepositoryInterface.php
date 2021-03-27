<?php


namespace Classes\Repositories;


use Classes\Advertisement;
use Classes\Collections\AdvertisementCollection;
use Classes\Request\AdvertisementRequest;
use Classes\Request\CloseAdvertisement;
use Classes\Request\GetAdvertisementRequest;
use Classes\Request\MainAdvertisementRequest;
use Classes\Request\SetVote;
use Classes\User;

interface AdvertisementRepositoryInterface
{
    public function createAdvertisement(AdvertisementRequest $advertisementRequest, User $user);

    public function addRatingByAdvertisementId(SetVote $setVote): bool;

    public function getLastUserAdvertisement(User $user);

    public function getAdvertisementsByLimitAndOffsetAndType(
        int $limit,
        int $offset,
        GetAdvertisementRequest $advertisementRequest
    ): ?AdvertisementCollection;

    public function getAdvertisementById(int $id): ?Advertisement;

    public function getCountAdvertisementsByType(string $type): Int;

    public function getCountAdvertisements(): Int;

    public function getAdvertisementsByLimitAndOrder(MainAdvertisementRequest $mainAdvertisementRequest): ?AdvertisementCollection;

    public function getAdvertisementsByUser(User $user): ?AdvertisementCollection;

    public function updateRelevanceById(CloseAdvertisement $closeAdvertisement, string $value): bool;

    public function deleteAdvertisementByAdvertisementIdAndUserId(CloseAdvertisement $closeAdvertisement): bool ;
}