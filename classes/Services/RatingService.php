<?php


namespace Classes\Services;


use Classes\Rating;
use Classes\Repositories\AdvertisementRepositoryInterface;
use Classes\Repositories\RatingRepositoryInterface;
use Classes\Request\SetVote;

class RatingService
{
    protected RatingRepositoryInterface $ratingRepository;
    protected AdvertisementRepositoryInterface $advertisementRepository;

    public function __construct(
        RatingRepositoryInterface $ratingRepository, AdvertisementRepositoryInterface $advertisementRepository)
    {
        $this->ratingRepository = $ratingRepository;
        $this->advertisementRepository = $advertisementRepository;
    }

    public function ratingExist(SetVote $setVote): bool
    {
        return $this->ratingRepository->ratingExist($setVote);
    }

    public function setVote(SetVote $setVote): bool
    {
        if ($this->ratingRepository->addRating($setVote)) {
            return $this->advertisementRepository->addRatingByAdvertisementId($setVote);
        }
        return false;
    }

    public function getPositiveVoteByUserIdAndAdvertisementId(SetVote $setVote): ?Rating
    {
        return $this->ratingRepository->getRating($setVote);
    }
}