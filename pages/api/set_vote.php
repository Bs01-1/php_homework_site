<?php

use Classes\Repositories\RatingRepository;
use Classes\Request\SetVote;
use Classes\Services\RatingService;

global $mysqli;

$ratingRepository = new RatingRepository($mysqli);
$ratingService = new RatingService($ratingRepository);
$ratingRequest = new SetVote($_POST);

if (!$ratingService->ratingExist($ratingRequest)) {
    if ($ratingService->setVote($ratingRequest)) {
        echo 'Вы проголосовали!';
    } else {
        echo 'Произашла ошибка';
    }
} else {
    echo 'Вы уже голосовали!';
}