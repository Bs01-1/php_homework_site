<?php

use Classes\Advertisement;
use Classes\Core\Paginator;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\GetAdvertisementRequest;
use Classes\Services\AdvertisementService;

global $mysqli;

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisementRequest = new GetAdvertisementRequest($_POST);
$paginator = new Paginator($advertisementRequest->page);

$advertisements = $advertisementService->getAdvertisements($paginator);

/**
 * @var $item Advertisement
 */
foreach ($advertisements as $item):
?>
<div style="margin: 1px 0">
    <?=$item->title ?>
</div>
<?php endforeach; ?>


