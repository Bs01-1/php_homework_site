<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\RatingRepository;
use Classes\Request\GetAdvertisementRequest;
use Classes\Services\AdvertisementService;
use Classes\Services\RatingService;

global $mysqli;

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisementRequest = new GetAdvertisementRequest($_POST);

$ratingRepository = new RatingRepository($mysqli);
$ratingService = new RatingService($ratingRepository);

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
<div class="advertisement_block" id="<?=$ratingService->getVoteByAdvertisementId($advertisement)?>">
    <div class="advertisement_img_block">
        <div class="advertisement_rating">
            рейтинг : <?=$ratingService->getVoteByAdvertisementId($advertisement)?>
        </div>
        <?php if (isset($user)) : ?>
        <div class="advertisement_arrow">
            <p onclick="setAdvertisementVote('<?=$user->id. '.' . $item->id . '.1'?>')">▲</p>
            <p onclick="setAdvertisementVote('<?=$user->id. '.' . $item->id . '.0'?>')">▼</p>
        </div>
        <?php endif; ?>
        <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="123">
    </div>
    <div class="advertisement_content_block">
        <div class="advertisement_content_title">
            <b title="<?=$advertisement->title?>"><?=$advertisement->title?></b>
        </div>
        <div class="advertisement_content_address">
            Адрес : <?=$advertisement->address ?? null?>
        </div>
        <div class="advertisement_content_about">
            Описание : <?=$advertisement->about ?? null?>
        </div>
        <div class="advertisement_content_more">
            <a href="">Подробнее</a>
            <div class="advertisement_price">Цена: <?php if ($advertisement->price === 0){
                echo 'По договору.';
                } else echo $advertisement->price . 'Р';?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


