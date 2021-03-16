<?php


namespace Classes\Services;


use Classes\Rating;
use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\RatingRepositoryInterface;
use Classes\Request\SetVote;

class RatingService
{
    protected RatingRepositoryInterface $ratingRepository;

    public function __construct(RatingRepositoryInterface $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;
    }

    public function ratingExist(SetVote $setVote): bool
    {
        return $this->ratingRepository->ratingExist($setVote);
    }

    public function setVote(SetVote $setVote): bool
    {
        if ($this->ratingRepository->addRating($setVote)) {
            global $mysqli;
            $advertisementRepository = new AdvertisementRepository($mysqli);
            return $advertisementRepository->addRatingByAdvertisementId($setVote);
        }
        return false;
    }

    public function getPositiveVoteByUserIdAndAdvertisementId(SetVote $setVote): ?Rating
    {
        return $this->ratingRepository->getRating($setVote);
    }
}