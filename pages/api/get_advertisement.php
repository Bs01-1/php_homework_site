<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\GetAdvertisementRequest;
use Classes\Services\AdvertisementService;

global $mysqli;

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisementRequest = new GetAdvertisementRequest($_POST);
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
<div class="advertisement_block" id="<?=$item->user_id . '.' . $item->id?>">
    <div class="advertisement_img_block">
        <div class="advertisement_rating">
            рейтинг : <?=$advertisement->rating?>
        </div>
        <div class="advertisement_arrow">
            <p>▲</p>
            <p>▼</p>
        </div>
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


