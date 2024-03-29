<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Repositories\AdvertisementRepository;
use Classes\Request\AdvertisementRequest;
use Classes\Request\ImgRequest;
use Classes\Services\AdvertisementService;
use Classes\User;

global $mysqli;
/**
 * @var $user User
 */
global $user;

if (!isset($user)) {
    return require_once 'pages/404.php';
}

if (isset($_POST['send_advertisement']) && $_FILES){
    $imgRequest = new ImgRequest($_FILES['imgs']);
    $advertisementRequest = new AdvertisementRequest($_POST);
    $fileManager = new FileManager();
    updateAdvertisementSession(true, $advertisementRequest);

    if (mb_strlen($advertisementRequest->title) <= 8){
        $err = 'Заголовок слишком короткий!';
    } else if (mb_strlen($advertisementRequest->address) <= 10){
        $err = 'Адрес слишком короткий!';
    } else if ($advertisementRequest->about === 'Введите описание недвижимости' || mb_strlen($advertisementRequest->about) <= 30){
        $err = 'Описание слишком короткое!';
    } else if (count($imgRequest->name) > 10) {
        $err = 'Слишком много документов! Можно не более 10';
    } else if (!$fileManager->isCorrectFormat($imgRequest)){
        $err = 'Такой формат документов не поддерживается!';
    } else if (!$fileManager->isCorrectSize($imgRequest)){
        $err = 'Слишком большой документ';
    } else if ($advertisementRequest->price === null){
        $err = 'Цена не указана!';
    }

    if (!isset($err)){
        global $user;

        $advertisementRepository = new AdvertisementRepository($mysqli);
        $advertisementService = new AdvertisementService($advertisementRepository);

        if ($advertisementService->createAdvertisement($advertisementRequest, $user)){
            updateAdvertisementSession(false, $advertisementRequest);

            $advertisement = new Advertisement();
            $advertisement = $advertisementService->getLastUserAdvertisement($user);

            if ($fileManager->addNewAdvertisementImg($imgRequest, $advertisement)){
                header("Location: /");
                return;
            }
        }
    }
}

function updateAdvertisementSession (bool $bool, AdvertisementRequest $advertisementRequest){
    if ($bool){
        $_SESSION['title'] = $advertisementRequest->title;
        $_SESSION['address'] = $advertisementRequest->address;
        $_SESSION['about'] = $advertisementRequest->about;
        $_SESSION['price'] = $advertisementRequest->price;
    } else {
        $_SESSION['title'] = null;
        $_SESSION['address'] = null;
        $_SESSION['about'] = null;
        $_SESSION['price'] = null;
    }
}
?>
<body>
    <form method="post" class="add_advertisement_block_main" enctype="multipart/form-data">
        <span>Добавить объявление</span>
        <div class="add_advertisement_block">
            <input required class="small_input" type="text" name="title" placeholder="Введите заголовок вашего объявления"
                   value="<?=$_SESSION['title'] ?? null ?>">
            <input required class="small_input" type="text" name="address" placeholder="Введите адрес недвижимости"
                   value="<?=$_SESSION['address'] ?? null ?>">
            <textarea class="small_input add_advertisement_textarea" type="text" name="about" onclick="textareaClear(this)"
                ><?=$_SESSION['about'] ?? 'Введите описание недвижимости' ?></textarea>
            <div>
                <input required class="small_input" type="radio" name="type" value="sale" id="sale">
                <label for="sale">Продажа</label>
                <input required class="small_input" type="radio" name="type" value="rentals" id="rentals">
                <label for="rentals">Аренда</label>
            </div>
            <input required class="small_input" type="number" name="price" placeholder="Введите цену"
                   value="<?=$_SESSION['price'] ?? null ?>">
            <div class="add_advertisement_img_block">
                <input multiple style="display: none" type="file" id="advertisement_img" name="imgs[]">
                <label class="small_input" for="advertisement_img">Добавить изображения</label>
                <div class="small_input add_advertisement_img_btn" onclick="preview()">Показать изображения</div>
            </div>
            <div class="add_advertisement_img_preview" id="preview"></div>
            <div class="advertisement_err"><?=$err ?? null ?></div>
            <input class="small_input" name="send_advertisement" type="submit" value="Добавить объявление">
        </div>
    </form>
</body>