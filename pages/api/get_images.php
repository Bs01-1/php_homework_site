<?php

use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\GetImages;
use Classes\Services\AdvertisementService;

global $mysqli;

$advertisementRequest = new GetImages($_POST);
$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisement = $advertisementService->getAdvertisementById($advertisementRequest->id);
$fileManager = new FileManager();

if ($fileManager->getCountImagesByAdvertisementId($advertisement) > 1){
    $imagesPaths = $fileManager->getImagesPathsByAdvertisementId($advertisement);
    echo json_encode($imagesPaths);
}
return false;

