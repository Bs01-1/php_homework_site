<?php


namespace Classes\Repositories;


use Classes\Rating;
use Classes\Request\SetVote;

interface RatingRepositoryInterface
{
    public function ratingExist(SetVote $setVote): bool;

    public function addRating(SetVote $setVote): bool;

    public function getRating(SetVote $setVote): ?Rating;
}