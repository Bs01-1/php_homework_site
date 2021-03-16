<?php

use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\RatingRepository;
use Classes\Request\SetVote;
use Classes\Services\AdvertisementService;
use Classes\Services\RatingService;

global $mysqli;

$ratingRepository = new RatingRepository($mysqli);
$ratingService = new RatingService($ratingRepository);
$ratingRequest = new SetVote($_POST);

if (!$ratingService->ratingExist($ratingRequest)) {
    if ($ratingService->setVote($ratingRequest)) {
        $answer = 'Вы проголосовали!.';
    } else {
        $answer = 'Произашла ошибка!';
    }
} else {
    $answer = 'Вы уже голосовали!.';
}

if ($answer !== 'Произашла ошибка!') :
    global $mysqli;

    $advertisementRepository = new AdvertisementRepository($mysqli);
    $advertisementService = new AdvertisementService($advertisementRepository);
    echo $answer .
        'Рейтинг : ' . $advertisementService->getAdvertisementRatingById($ratingRequest->advertisement_id);
?>
.<p onclick="addNotification('Вы уже проголосовали!')"
   class="<?=($ratingRequest->positive_vote) ? 'text_color_green' : 'text_color_red'?>">
    <?=($ratingRequest->positive_vote) ? '▲' : '▼'?>
</p>
<?php endif; ?>