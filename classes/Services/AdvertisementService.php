<?php


namespace Classes\Services;


use Classes\Advertisement;
use Classes\Collections\AdvertisementCollection;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepositoryInterface;
use Classes\Request\AdvertisementRequest;
use Classes\Request\CloseAdvertisement;
use Classes\Request\EditAdvertisement;
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

    public function getAdvertisementById(int $id): ?Advertisement
    {
        return $this->advertisementRepository->getAdvertisementById($id);
    }

    public function getAdvertisementsByUser(User $user): ?AdvertisementCollection
    {
        return $this->advertisementRepository->getAdvertisementsByUser($user);
    }

    public function closeAdvertisementByUserId(CloseAdvertisement $closeAdvertisement): bool
    {
        return $this->advertisementRepository->updateRelevanceById($closeAdvertisement->advertisement_id, 'close');
    }

    public function updateAdvertisementRelevanceToWait(CloseAdvertisement $closeAdvertisement): bool
    {
        return $this->advertisementRepository->updateRelevanceById($closeAdvertisement->advertisement_id, 'wait');
    }

    public function updateAdvertisementRelevanceToOpen(int $advertisementId): bool
    {
        return $this->advertisementRepository->updateRelevanceById($advertisementId, 'open');
    }

    public function deleteAdvertisementByUserId(CloseAdvertisement $closeAdvertisement): bool
    {
        return $this->advertisementRepository->deleteAdvertisementByAdvertisementIdAndUserId($closeAdvertisement);
    }

    public function updatePublicAdvertisementInfo(EditAdvertisement $advertisement): bool
    {
        return $this->advertisementRepository->updatePublicAdvertisementInfo($advertisement);
    }
}