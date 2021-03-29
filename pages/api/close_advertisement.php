<?php

use Classes\Repositories\AdvertisementRepository;
use Classes\Request\CloseAdvertisement;
use Classes\Services\AdvertisementService;

global $mysqli;
global $user;

$advertisementRequest = new CloseAdvertisement($_POST);
$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);

$answer =  ($user->id === $advertisementRequest->user_id) ?
    ($advertisementService->closeAdvertisementByUserId($advertisementRequest)) ? 'Объявление закрыто!' : 'Ошибка!'
        : 'Ошибка пользователя';
echo $answer;