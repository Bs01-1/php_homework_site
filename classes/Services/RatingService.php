<?php


namespace Classes\Services;



use Classes\Advertisement;
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
        return $this->ratingRepository->addRating($setVote);
    }

    public function getVoteByAdvertisementId(Advertisement $advertisement): int
    {
        return $this->ratingRepository->getRatingByAdvertisementId($advertisement);
    }
}