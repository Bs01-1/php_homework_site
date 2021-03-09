<?php

use Classes\Repositories\InfoRepository;
use Classes\Services\InfoServices;

global $mysqli;

$infoRepository = new InfoRepository($mysqli);
$infoService = new InfoServices($infoRepository);
$infoCollection = $infoService->getAll();

?>

<div class="info_block">
    <?php foreach ($infoCollection as $item): ?>
         <div class="info_content_block" id="<?=$item->name?>">
                <div class="info_title"><?=$item->title ?? null?></div>
                <div class="info_content"><?=$item->content ?? null?></div>
         </div>
    <?php endforeach; ?>
</div>
