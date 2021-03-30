<?php

use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\DeleteImg;
use Classes\Services\AdvertisementService;

global $user;
global $mysqli;

$request = new DeleteImg($_POST);
$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisement = $advertisementService->getAdvertisementById($request->advertisement_id);

if ($user->id !== $advertisement->user_id) {
    echo json_encode(['answer' => false]);
    return;
}

$fileManager = new FileManager();
echo ($fileManager->deleteImg($advertisement, $request->img_path)) ? json_encode(['answer' => true]) : json_encode(['answer' => false]);
