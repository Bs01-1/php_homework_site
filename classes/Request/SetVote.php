<?php


namespace Classes\Request;


class SetVote extends HttpRequest
{
    public int $user_id;
    public int $advertisement_id;
    public string $positive_vote;
}