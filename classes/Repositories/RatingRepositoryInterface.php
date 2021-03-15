<?php


namespace Classes\Repositories;


use Classes\Advertisement;
use Classes\Request\SetVote;

interface RatingRepositoryInterface
{
    public function ratingExist(SetVote $setVote): bool;

    public function addRating(SetVote $setVote): bool;

    public function getRatingByAdvertisementId(Advertisement $advertisement): int;
}