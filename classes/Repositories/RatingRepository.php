<?php


namespace Classes\Repositories;


use Classes\Advertisement;
use Classes\Request\SetVote;
use Classes\User;

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

    public function getRatingByAdvertisementId(Advertisement $advertisement): int
    {
        $result = $this->connection->query("SELECT voteY - voteF as vote FROM
            (SELECT COUNT(*) as voteY FROM rating WHERE advertisement_id = {$advertisement->id} AND 
            positive_vote = 1)a,
            (SELECT COUNT(*) as voteF FROM rating WHERE advertisement_id = {$advertisement->id} AND 
            positive_vote = 0)b");
        return $result->fetch_assoc()['vote'];
    }
}