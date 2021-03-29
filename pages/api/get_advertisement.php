<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\RatingRepository;
use Classes\Request\GetAdvertisementRequest;
use Classes\Request\SetVote;
use Classes\Services\AdvertisementService;
use Classes\Services\RatingService;

global $mysqli;

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisementRequest = new GetAdvertisementRequest($_POST);

$ratingRepository = new RatingRepository($mysqli);
$ratingService = new RatingService($ratingRepository, $advertisementRepository);

$paginator = new Paginator($advertisementRequest->page);
$fileManager = new FileManager();

$advertisements = $advertisementService->getAdvertisements($paginator, $advertisementRequest);
if (!$advertisements){
    return;
}

/**
 * @var $item Advertisement
 */
foreach ($advertisements as $item):
    $advertisement = Advertisement::createFromObject($item);

?>
<div class="advertisement_block" id="<?=$advertisement->id?>r_a">
    <div class="advertisement_img_block">
        <div class="advertisement_rating">
            Рейтинг : <?=$advertisement->rating?>
        </div>
        <?php if ($advertisement->relevance === 'close') : ?>
        <div class="status_advertisement"><?='Закрыт'?></div>
        <?php endif; ?>
        <?php
        if (isset($user) && $advertisement->relevance !== 'close') :
            $ratingRequest = new SetVote([
                'advertisement_id' => $advertisement->id,
                'user_id' => $user->id
            ]);
            $upArrow = '▲';
            $downArrow = '▼';

            if (!$ratingService->ratingExist($ratingRequest)) :
        ?>
        <div class="advertisement_arrow">
            <p onclick="setAdvertisementVote('<?=$user->id. '.' . $item->id . '.1'?>')" id="arrow1"
                class="advertisement_arrow_vote"><?=$upArrow?></p>
            <p onclick="setAdvertisementVote('<?=$user->id. '.' . $item->id . '.0'?>')" id="arrow0"
               class="advertisement_arrow_vote"><?=$downArrow?></p>
        </div>
        <?php else :
            if ($positiveVote = $ratingService->getPositiveVoteByUserIdAndAdvertisementId($ratingRequest)) :
        ?>
        <div class="advertisement_arrow">
            <p onclick="addNotification('Вы уже проголосовали!')"
               class="<?=($positiveVote->positive_vote) ? 'text_color_green' : 'text_color_red'?>">
                <?=($positiveVote->positive_vote) ? $upArrow : $downArrow?>
            </p>
        </div>
        <?php endif; endif; endif; ?>
        <a href="id<?=$advertisement->id?>">
            <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="123">
        </a>
    </div>
    <div class="advertisement_content_block">
        <a href="id<?=$advertisement->id?>" class="advertisement_content_title">
            <b title="<?=$advertisement->title?>"><?=$advertisement->title?></b>
        </a>
        <div class="advertisement_content_address">
            Адрес : <?=$advertisement->address ?? null?>
        </div>
        <div class="advertisement_content_about">
            Описание : <?=$advertisement->about ?? null?>
        </div>
        <div class="advertisement_content_more">
            <a href="id<?=$advertisement->id?>">Подробнее</a>
            <div class="advertisement_price">Цена: <?php if ($advertisement->price === 0){
                echo 'По договору.';
                } else echo $advertisement->price . 'Р';?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


