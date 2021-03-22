<?php


namespace Classes;


class Rating extends Model
{
    public int $id;
    public int $user_id;
    public int $advertisement_id;
    public bool $positive_vote;
}