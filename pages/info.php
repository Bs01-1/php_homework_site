<?php

use Classes\Repositories\InfoRepository;
use Classes\Services\InfoServices;

global $mysqli;

$termsOfUseRepository = new InfoRepository($mysqli);
$termsOfUseService = new InfoServices($termsOfUseRepository);
$termsOfUse = $termsOfUseService->getInfo('termsOfUse');

?>

<div class="info_block">
    <div class="info_termsOfUse_block" id="rule">
        <div class="info_termsOfUse_title"><?=$termsOfUse->title ?? null?></div>
        <div class="info_termsOfUse_content"><?=$termsOfUse->content ?? null?></div>
    </div>
</div>
