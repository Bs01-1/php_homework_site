<?php

use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\BuyRepository;
use Classes\Request\CloseAdvertisement;
use Classes\Services\AdvertisementService;
use Classes\Services\BuyService;

global $mysqli;
global $user;

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);

$buyRequest = new CloseAdvertisement($_POST);
$buyRepository = new BuyRepository($mysqli);
$buyService = new BuyService($buyRepository, $advertisementRepository);

$advertisement = $advertisementService->getAdvertisementById($buyRequest->advertisement_id);

$answer =  ($user->id === $buyRequest->user_id && $advertisement->relevance !== 'close') ?
    ($buyService->addBuyRequest($buyRequest)) ? $advertisementService->updateAdvertisementRelevanceToWait($buyRequest) ?
        'Заявка была отправлена. Ожидайте, когда вам ее одобрят' : 'Ошибка обновления!'
        : 'Ошибка базы' : 'Ошибка пользователя';
echo $answer;