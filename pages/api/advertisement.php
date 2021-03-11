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

/**
 * @var $item Advertisement
 */
foreach ($advertisements as $item):
    $advertisement = Advertisement::createFromObject($item);
?>
<div class="advertisement_block" id="<?=$item->user_id . '.' . $item->id?>">
    <div class="advertisement_img_block">
        <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="123">
    </div>
    <a href="">
        <div class="advertisement_content_block">
            <div class="advertisement_content_title">
                <b title="<?=$advertisement->title?>"><?=$advertisement->title?></b>
            </div>
        </div>
    </a>
</div>
<?php endforeach; ?>


