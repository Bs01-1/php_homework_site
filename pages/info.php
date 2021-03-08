<?php

use Classes\Collections\InfoCollection;
use Classes\Repositories\InfoRepository;
use Classes\Services\InfoServices;

global $mysqli;

$termsOfUseRepository = new InfoRepository($mysqli);
$termsOfUseService = new InfoServices($termsOfUseRepository);
$termsOfUse = $termsOfUseService->getInfo('termsOfUse');

$termsOfUse1 = clone $termsOfUse;
$termsOfUse2 = clone $termsOfUse;
$termsOfUse3 = clone $termsOfUse;

$termsOfUseCollection = new InfoCollection();
$termsOfUseCollection->addItem($termsOfUse);
$termsOfUseCollection->addItem($termsOfUse1);
$termsOfUseCollection->addItem($termsOfUse2);
$termsOfUseCollection->addItem($termsOfUse3);

?>

<div class="info_block">
    <div class="info_termsOfUse_block" id="rule">
        <?php foreach ($termsOfUseCollection as $teemOfUse): ?>
            <div class="info_termsOfUse_title"><?=$termsOfUse->title ?? null?></div>
            <div class="info_termsOfUse_content"><?=$termsOfUse->content ?? null?></div>
        <?php endforeach; ?>
    </div>
</div>
