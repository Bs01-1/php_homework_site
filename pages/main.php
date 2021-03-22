<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\MainAdvertisementRequest;
use Classes\Services\AdvertisementService;

global $mysqli;

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisementsCount = $advertisementService->getCountAdvertisements();

$fileManager = new FileManager();

$advertisementSetting = [
        ['name' => 'Самые популярные объявления', 'orderBy' => 'rating', 'desc' => true],
        ['name' => 'Самые свежие объявления', 'orderBy' => 'createdAt', 'desc' => true],
        ['name' => 'Самые старые объявления', 'orderBy' => 'createdAt', 'desc' => false],
]
?>
<div class="main_content_block">
    <div class="main_content_info">
        <?=$advertisementsCount?> объявлений о продаже и аренде жилой, загородной и коммерческой недвижимости
    </div>
    <?php
        for ($i = 0; $i < count($advertisementSetting); $i++) :
            $advertisementRequest = new MainAdvertisementRequest($advertisementSetting[$i]);
            $advertisements = $advertisementService->getAdvertisementByLimitAndOrder($advertisementRequest);
     ?>
    <div class="content_block">
        <div class="content_title"><?=$advertisementRequest->name?></div>
        <div class="content_box">
     <?php
            /**
             * @var $item Advertisement
             */
            foreach ($advertisements as $item) :
    ?>
            <a href="id<?=$item->id?>" class="content">
                <div class="advertisement_rating main_rating">
                    Рейтинг : <?=$item->rating?>
                </div>
                <div class="content_img" title="<?=$item->title?> || Цена : <?=$item->price?>">
                    <img src="<?=$fileManager->getFirstImgPathByAdvertisement($item)?>" alt="">
                </div>
                <p><?=$item->title?></p>
            </a>
    <?php endforeach;  ?>
        </div>
    </div>
    <?php endfor; ?>
</div>