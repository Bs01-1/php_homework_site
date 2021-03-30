<?php

use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\ImgRequest;
use Classes\Services\AdvertisementService;

global $mysqli;
global $user;

$requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
$advertisementId = intval(str_replace('/', '', str_replace('edit_id', '', $requestUrl)));

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);
$advertisement = $advertisementService->getAdvertisementById($advertisementId);
if (!$advertisement || $user->id !== $advertisement->user_id) {
    return require_once 'pages/404.php';
}

$fileManager = new FileManager();
$images = $fileManager->getImagesPathsByAdvertisementId($advertisement);
$imgPath = '/img/content/'.$advertisement->type.'/'.$advertisement->user_id.'/'.$advertisement->id.'/';

if (isset($_POST['add_img'])) {
    $imgRequest = new ImgRequest($_FILES['imgs']);

    if (!$fileManager->isCorrectSize($imgRequest) || !$fileManager->isCorrectFormat($imgRequest)) {
        $err = 'Ошибка формата или размера!';
    }
    if ($fileManager->getCountImagesByAdvertisementId($advertisement) + count($imgRequest->name) > 10) {
        $err = 'Нельзя больше 10 изображений!';
    }

    if (!isset($err)) {
        $fileManager->addNewAdvertisementImg($imgRequest, $advertisement);
        header("Location: /edit_id{$advertisement->id}");
    }
}
?>
<div class="edit_id_main_block">
    <div class="edit_id_title">Редактирование изображений</div>
    <div class="edit_id_back"><a href="id<?=$advertisement->id?>">Назад</a></div>
    <?php if (!empty($images)) : ?>
    <div class="edit_id_img_block">
        <?php for ($i = 0; $i < count($images); $i++) : ?>
        <div class="edit_id_img" id="<?=$images[$i]?>">
            <img src="<?=$imgPath.$images[$i]?>" alt="">
            <div class="edit_id_delete_img" onclick="delete_image('<?=$images[$i]?>')">x</div>
        </div>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    <form method="post" class="edit_id_add_img_block" enctype="multipart/form-data">
        <input multiple style="display: none" type="file" id="advertisement_img" name="imgs[]">
        <label class="edit_id_add_img_input" for="advertisement_img">Добавить изображения</label>
        <input class="small_input" type="submit" id="advertisement_img" name="add_img" value="Добавить">
        <div class="advertisement_err"><?=$err ?? null ?></div>
    </form>
    <div class="edit_id_title">Редактирование информацию объявления</div>
    <form method="post" class="edit_id_form_block">
        <p>Заголовок объявления </p>
        <input class="edit_input" type="text" name="title" value="<?=$advertisement->title?>">
        <p>Описание объявления </p>
        <input class="edit_input" type="text" name="title" value="<?=$advertisement->about?>">
    </form>
</div>
<script type="text/javascript">
    async function delete_image(id) {
        let formData = new FormData();
        formData.append('advertisement_id', <?=$advertisement->id ?? 0?>);
        formData.append('img_path', id);

        let result = await (await fetch('/api/delete_img', {
            method: 'POST',
            body: formData
        })).json();

        if (result.answer) {
            let img_block = document.getElementById(id);
            img_block.parentNode.removeChild(img_block);
            addNotification('Изображение удалено!');
        }
    }
</script>
