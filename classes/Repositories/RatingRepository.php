<?php


namespace Classes\Repositories;


use Classes\Rating;
use Classes\Request\SetVote;

class RatingRepository extends Repository implements RatingRepositoryInterface
{

    public function ratingExist(SetVote $setVote): bool
    {
        $result = $this->connection->query("SELECT * FROM rating WHERE user_id = {$setVote->user_id} AND 
                advertisement_id = {$setVote->advertisement_id}");

        if (!$result->num_rows){
            return false;
        }
        return true;

    }

    public function addRating(SetVote $setVote): bool
    {
        $positiveVote = filter_var($setVote->positive_vote, FILTER_VALIDATE_BOOLEAN);
        $positiveVote = $positiveVote ? 1 : 0;

        return $this->connection->query("INSERT INTO rating (user_id, advertisement_id, positive_vote)
                VALUES ({$setVote->user_id}, {$setVote->advertisement_id}, {$positiveVote})");
    }

    public function getRating(SetVote $setVote): ?Rating
    {
        $rating = $this->connection->query("SELECT * FROM rating WHERE user_id = {$setVote->user_id} 
            AND advertisement_id = {$setVote->advertisement_id}");

        if (!$rating || $rating->num_rows < 1){
            return null;
        }
        $ratingArray = $rating->fetch_assoc();
        $ratingArray['positive_vote'] = $ratingArray['positive_vote'] ? true : false;
        $rating->free_result();

        return Rating::createFromArray($ratingArray);
    }
}