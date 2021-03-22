<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\RatingRepository;
use Classes\Repositories\UserRepository;
use Classes\Request\MainAdvertisementRequest;
use Classes\Request\SetVote;
use Classes\Services\AdvertisementService;
use Classes\Services\RatingService;
use Classes\Services\UserService;

global $mysqli;

$requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
$advertisementId = intval(str_replace('/', '', str_replace('id', '', $requestUrl)));

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisement = $advertisementService->getAdvertisementById($advertisementId);

$ratingRepository = new RatingRepository($mysqli);
$ratingService = new RatingService($ratingRepository, $advertisementRepository);

$userRepository = new UserRepository($mysqli);
$userService = new UserService($userRepository);
$advertisementUser = $userService->getUserById($advertisement->user_id);

$fileManager = new FileManager();
?>
<div class="advertisement_page_block_main" id="<?=$advertisement->user_id . '.' . $advertisement->id . '.' . $advertisement->type?>">
    <div class="advertisement_page_header">
        <div class="advertisement_img_block">
            <?php if ($fileManager->getCountImagesByAdvertisementId($advertisement) > 1) : ?>
            <div class="advertisement_img_arrow left" onclick="imageBack()"><<</div>
            <div class="advertisement_img_arrow right" onclick="imageUp()">>></div>
            <?php endif; ?>
            <div class="advertisement_rating">
                Рейтинг : <?=$advertisement->rating?>
            </div>
            <?php if ($advertisement->relevance !== 'open') : ?>
                <div class="status_advertisement">Закрыт</div>
            <?php endif; ?>
            <?php
            if (isset($user)) :
                $ratingRequest = new SetVote([
                    'advertisement_id' => $advertisement->id,
                    'user_id' => $user->id
                ]);
                $upArrow = '▲';
                $downArrow = '▼';

                if (!$ratingService->ratingExist($ratingRequest)) :
                    ?>
                    <div class="advertisement_arrow">
                        <p onclick="setAdvertisementVote('<?=$user->id. '.' . $advertisement->id . '.1'?>')" id="arrow1"
                           class="advertisement_arrow_vote"><?=$upArrow?></p>
                        <p onclick="setAdvertisementVote('<?=$user->id. '.' . $advertisement->id . '.0'?>')" id="arrow0"
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
            <div id="img_block">
                <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="">
            </div>
        </div>
        <div class="advertisement_page_header_info">
            <div class="advertisement_title"><?=$advertisement->title?></div>
            <div class="advertisement_address">Адрес : <?=$advertisement->address?></div>
            <div class="advertisement_phone">Телефон : <?=$advertisementUser->phone?>
                <div class="advertisement_page_price">Цена : <?=$advertisement->price?></div>
            </div>
        </div>
    </div>
    <div class="advertisement_page_content">
        <div class="advertisement_page_content_title">Описание</div>
        <div class="advertisement_page_content_about"><?=$advertisement->about?></div>
    </div>
    <div class="advertisement_page_footer">
        <div class="advertisement_page_footer_title">
            Рекомендуем к просмотрю популярные объявления.
        </div>
        <div class="content_box">
        <?php
        $advertisementSetting = ['name' => 'Самые популярные объявления', 'orderBy' => 'rating', 'desc' => true];
        $advertisementRequest = new MainAdvertisementRequest($advertisementSetting);
        $advertisements = $advertisementService->getAdvertisementByLimitAndOrder($advertisementRequest);
        /**
         * @var $item Advertisement
         */
        foreach ($advertisements as $item):?>
            <a href="id<?=$item->id?>" class="content">
                <div class="advertisement_rating main_rating">
                    Рейтинг : <?=$item->rating?>
                </div>
                <div class="content_img" title="<?=$item->title?> || Цена : <?=$item->price?>">
                    <img src="<?=$fileManager->getFirstImgPathByAdvertisement($item)?>" alt="">
                </div>
                <p><?=$item->title?></p>
            </a>
        <?php endforeach;?>
        </div>
    </div>
</div>
<script type="text/javascript">
    let imagesPathsArray = [];
    let imagesCount = 0;
    let user_id = '';
    let id = '';
    let type = '';
    let imgBlock = '';
    async function chanceImages() {
        let formData =  new FormData();
        let advertisementInfo = (document.querySelector('.advertisement_page_block_main')).id;
        let advertisementInfoArray = advertisementInfo.split('.');

        user_id = advertisementInfoArray[0];
        id = advertisementInfoArray[1];
        type = advertisementInfoArray[2];
        imgBlock = document.getElementById('img_block');

        formData.append('user_id', user_id);
        formData.append('id', id);

        let result = await (await fetch('/api/get_images', {
            method: 'POST',
            body: formData
        })).json();

        for (let i = 0; i < result.length; i++) {
            let img = document.createElement('img');
            img.src = `/img/content/${type}/${user_id}/${id}/${result[i]}`;
            imagesPathsArray.push(img);
        }
        imagesCount = result.length;
    }

    async function imageUp() {
        if (imagesPathsArray.length === 0) await chanceImages();

        imgBlock.innerHTML = '';
        imagesCount++;
        if (imagesCount >= imagesPathsArray.length)
            imagesCount = 0;
        imgBlock.appendChild(imagesPathsArray[imagesCount]);
    }

    async function imageBack() {
        if (imagesPathsArray.length === 0) await chanceImages();

        imgBlock.innerHTML = '';
        imagesCount -= 1;
        if (imagesCount < 0) {
            imagesCount = imagesPathsArray.length - 1;
        }
        imgBlock.appendChild(imagesPathsArray[imagesCount]);
    }

    async function setAdvertisementVote(params) {
        let param = params.split('.');

        let formData = new FormData();
        formData.append('user_id', param[0]);
        formData.append('advertisement_id', param[1]);
        formData.append('positive_vote', param[2]);

        let result = (await fetch('/api/set_vote', {
            method: 'POST',
            body: formData
        })).text();

        let resultArray =  (await result).split('.');
        if (resultArray[2] !== undefined) {
            let arrowBlock = document.querySelector('.advertisement_arrow');
            arrowBlock.innerHTML = resultArray[2];

            let ratingBlock = document.querySelector('.advertisement_rating');
            ratingBlock.innerHTML = resultArray[1];
        }

        addNotification(resultArray[0]);
    }
</script>

