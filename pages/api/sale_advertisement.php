<?php

use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\BuyRepository;
use Classes\Services\AdvertisementService;
use Classes\Services\BuyService;

global $mysqli;
global $user;

if (!$buyId = $_POST['buy_id']) {
    echo 'Ошибка объявления!';
    return;
}

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$buyRepository = new BuyRepository($mysqli);
$buyService = new BuyService($buyRepository, $advertisementRepository);

if (!$buy = $buyService->getBuyById($buyId)) {
    echo 'Обновите страницу.';
    return;
}
$advertisement = $advertisementService->getAdvertisementById($buy->advertisement_id);
if ($advertisement->user_id !== $user->id) {
    echo 'Ошибка пользователя!';
    return;
}

if ($buyService->saleAdvertisement($buy, $advertisement)) {
    echo 'Объявление продано!';
    return;
}
echo 'Ошибка';