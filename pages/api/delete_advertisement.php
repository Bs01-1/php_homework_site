<?php

use Classes\Repositories\AdvertisementRepository;
use Classes\Request\CloseAdvertisement;
use Classes\Services\AdvertisementService;

global $mysqli;
global $user;

$advertisementRequest = new CloseAdvertisement($_POST);
$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);

$advertisement = $advertisementService->getAdvertisementById($advertisementRequest->advertisement_id);

$answer =  ($user->id === $advertisementRequest->user_id && $advertisement->relevance === 'close') ?
    ($advertisementService->deleteAdvertisementByUserId($advertisementRequest)) ? 'Объявление удалено!' : 'Ошибка!'
    : 'Ошибка пользователя';
echo $answer;