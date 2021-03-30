<?php

use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\CloseAdvertisement;
use Classes\Services\AdvertisementService;

global $mysqli;
global $user;

$advertisementRequest = new CloseAdvertisement($_POST);
$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);

$fileManager = new FileManager();
$advertisement = $advertisementService->getAdvertisementById($advertisementRequest->advertisement_id);
$images = $fileManager->getImagesPathsByAdvertisementId($advertisement);


if ($user->id === $advertisementRequest->user_id && $advertisement->relevance === 'close') {
    $answer = ($advertisementService->deleteAdvertisementByUserId($advertisementRequest) &&
        (!empty($images)) ? $fileManager->deleteImages($advertisement, $images) : true) ? 'Объявление удалено' : 'Ошибка';
    echo $answer;
    return;
}
echo 'Ошибка!';
return;