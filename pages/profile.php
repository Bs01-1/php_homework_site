<?php

use Classes\Advertisement;
use Classes\Advertisement\FileManager;
use Classes\Buy;
use Classes\Repositories\AdvertisementRepository;
use Classes\Repositories\BuyRepository;
use Classes\Repositories\UserRepository;
use Classes\Request\ProfileRequest;
use Classes\Services\AdvertisementService;
use Classes\Services\BuyService;
use Classes\Services\ProfileService;
use Classes\Services\UserService;
use Classes\User;

global $mysqli;
/**
 * @var $user User
 */
global $user;

$requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
$userId = intval(str_replace('/', '', str_replace('profile', '', $requestUrl)));

$userRepository = new UserRepository($mysqli);
$userService = new UserService($userRepository);

$advertisementRepository = new AdvertisementRepository($mysqli);
$advertisementService = new AdvertisementService($advertisementRepository);

$buyRepository = new BuyRepository($mysqli);
$buyService = new BuyService($buyRepository, $advertisementRepository);

$fileManager = new FileManager();

if (!$profileUser = $userService->getUserById($userId)) {
    return require_once 'pages/404.php';
}
if (!isset($user) || $user->id !== $profileUser->id) {
    return require_once 'pages/404.php';
}

if (isset($_POST['profile_update'])) {
    $profileRequest = new ProfileRequest($_POST);

    if (mb_strlen($profileRequest->city) <= 2 && mb_strlen($profileRequest->city) >= 30) {
        $err = 'Это не город.';
    } else if (mb_strlen($profileRequest->phone) <= 5 || mb_strlen($profileRequest->phone) >= 20) {
        $err = 'Кривой номер';
    } else if (mb_strlen($profileRequest->password) !== 0) {
        if (!$userService->correctPassword($profileUser, $profileRequest)) {
            $err = 'Неправильный пароль!';
        } else if (mb_strlen($profileRequest->newPassword) <= 8 || mb_strlen($profileRequest->newPassword) >= 255) {
            $err = 'Такой пароль нельзя!';
        } else if ($profileRequest->newPassword !== $profileRequest->newPasswordRepeat) {
            $err = 'Пароли не совпадают!';
        }
    }

    if (!isset($err)) {
        $profileService = new ProfileService($userRepository);
        if ($profileUser->city !== $profileRequest->city || $profileUser->phone !== $profileRequest->phone) {
            $profileService->UpdateProfileData($profileRequest);
        }
        if ($userService->correctPassword($profileUser, $profileRequest) &&
            $profileRequest->newPassword === $profileRequest->newPasswordRepeat) {
            $profileService->UpdatePassword($profileRequest);
        }
        header("Location: /profile{$profileUser->id}");
        return;
    }
}
?>
<div class="profile_main_block">
    <div class="profile_header_block">
        <div id="profile">(<?=$profileUser->id?>) Профиль : <?=$profileUser->nickname?> (<?=$profileUser->date?>)</div>
        <div>Пол: <?=($profileUser->gender === 'male' ? 'мужской' : 'женский')?>, Дата регистрации : <?=$profileUser->createdAt?></div>
    </div>
    <form method="post" class="profile_header_info">
        <div><p>Город : </p><input class="profile_input" type="text" name="city" value="<?=$profileUser->city?>"></div>
        <div><p>Телефон : </p><input class="profile_input" type="text" name="phone" value="<?=$profileUser->phone?>"></div>
        <div class="profile_header_password_title">Смена пароля</div>
        <div><p>Старый пароль : </p><input class="profile_input" type="password" name="password" ></div>
        <div><p>Новый Пароль : </p><input class="profile_input" type="password" name="newPassword"></div>
        <div><p>Повторите новый пароль : </p><input class="profile_input" type="password" name="newPasswordRepeat"></div>
        <div class="profile_err"><?=$err ?? null ?></div>
        <input type="hidden" name="id" value="<?=$profileUser->id?>">
        <input class="profile_input profile_submit" type="submit" name="profile_update" value="Сохранить">
    </form>
    <div class="profile_advertisement_block">
        <div class="profile_advertisement_title">Предложения</div>
        <?php if ($buys = $buyService->getBuysByUserWhereSuccessFalse($user)) : ?>
            <div class="profile_advertisement_mini_title">Ожидают вашего ответа</div>
            <?php
            /**
             * @var $item Buy
             */
            foreach ($buys as $item) :
                $advertisement = $advertisementService->getAdvertisementById($item->advertisement_id);
                $buyer = $userService->getUserById($item->user_id);
            ?>
                <div class="profile_advertisement_answer_block" id="<?=$item->id?>">
                    <a class="profile_advertisement_answer_img" title="Перейти к объявлению" href="id<?=$item->advertisement_id?>">
                        <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="">
                    </a>
                    <div class="profile_advertisement_answer_info">
                        <div class="profile_advertisement_answer_title"><?=$advertisement->title?></div>
                        <p>Покупатель : <?=$buyer->nickname?></p>
                        <p>Телефон : <?=$buyer->phone?></p>
                    </div>
                    <div class="profile_advertisement_answer_button_block">
                        <div class="profile_advertisement_answer_buttons"onclick="acceptBuy(<?=$item->id?>)">Принять</div>
                        <div class="profile_advertisement_answer_buttons" onclick="deleteBuy(<?=$item->id?>)">Отклонить</div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        <?php if ($buys = $buyService->getBuysByUserAndSuccess($user, false)) : ?>
        <div class="profile_advertisement_mini_title">Ожидаем ответ продовца</div>
        <?php
        /**
         * @var $item Buy
         */
        foreach ($buys as $item) :
            $advertisement = $advertisementService->getAdvertisementById($item->advertisement_id);
            $advertisementUser = $userService->getUserById($advertisement->user_id);
            ?>
            <div class="profile_advertisement_answer_block" id="<?=$item->id?>">
                <a class="profile_advertisement_answer_img" title="Перейти к объявлению" href="id<?=$item->advertisement_id?>">
                    <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="">
                </a>
                <div class="profile_advertisement_answer_info">
                    <div class="profile_advertisement_answer_title"><?=$advertisement->title?></div>
                    <p>Продавец: <?=$advertisementUser->nickname?></p>
                    <p>Телефон: <?=$advertisementUser->phone?></p>
                </div>
            </div>
        <?php endforeach; endif; ?>
        <?php if ($buys = $buyService->getBuysByUserAndSuccess($user, true)) : ?>
            <div class="profile_advertisement_mini_title">Купленные объявления</div>
            <?php
            /**
             * @var $item Buy
             */
            foreach ($buys as $item) : ?>
                <div class="profile_advertisement_answer_block" id="<?=$item->id?>">
                    <a class="profile_advertisement_answer_img" title="Перейти к объявлению" href="id<?=$item->advertisement_id?>">
                        <img src="<?=$fileManager->getFirstImgPathByAdvertisement($advertisement)?>" alt="">
                    </a>
                    <div class="profile_advertisement_answer_info">
                        <div class="profile_advertisement_answer_title"><?=$advertisement->title?></div>
                        <p>Продавец: <?=$advertisementUser->nickname?></p>
                        <p>Телефон: <?=$advertisementUser->phone?></p>
                    </div>
                </div>
        <?php endforeach; endif; ?>
        <div class="profile_advertisement_title">Ваши объявления</div>
        <?php
        $advertisements = $advertisementService->getAdvertisementsByUser($profileUser);
        if ($advertisements) : ?>
        <div class="profile_advertisement_mini_title">Активные объявления</div>
        <div class="profile_advertisement_box">
        <?php
        /**
         * @var $item Advertisement
         */
            foreach ($advertisements as $item) :
                if ($item->relevance === 'open') :
        ?>
            <div class="profile_advertisement" title="<?=$item->title?>" onmouseover="selectBlock(this)" onmouseleave="leave(this)">
                <img src="<?=$fileManager->getFirstImgPathByAdvertisement($item)?>" alt="">
                <a class="profile_advertisement_more" id="more" href="id<?=$item->id?>">Подробнее</a>
            </div>
        <?php endif; endforeach; ?>
        </div>
        <div class="profile_advertisement_mini_title">Закрытые объявления</div>
        <div class="profile_advertisement_box">
        <?php foreach ($advertisements as $item) :
            if ($item->relevance === 'close') :
                ?>
                <div class="profile_advertisement" title="<?=$item->title?>" onmouseover="selectBlock(this)" onmouseleave="leave(this)">
                    <img src="<?=$fileManager->getFirstImgPathByAdvertisement($item)?>" alt="">
                    <a class="profile_advertisement_more" id="more" href="<?='profile'.$profileUser->id?>" onclick="deleteAdvertisement(<?=$item->id?>)">Удалить</a>
                </div>
        <?php endif; endforeach; else :?>
            <div class="profile_advertisement_mini_title">У вас нет объявлений!</div>
        <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.title = (document.getElementById('profile')).innerHTML;

    function selectBlock(e) {
        let block = e.querySelector('#more');
        block.style.display = 'block';
    }

    function leave(e) {
        let block = e.querySelector('#more');
        block.style.display = 'none';
    }

    async function deleteAdvertisement(advertisement_id) {
        let formData = new FormData();
        formData.append('user_id', '<?=$profileUser->id?>');
        formData.append('advertisement_id', advertisement_id);

        let result = await fetch('/api/delete_advertisement', {
            method: 'POST',
            body: formData
        });

        addNotification(await result.text());
    }

    async function deleteBuy(buy_id) {
        let formData = new FormData();
        formData.append('buy_id', buy_id);

        let result = await fetch('/api/delete_buy', {
            method: 'POST',
            body: formData
        });

        addNotification(await result.text());
        let block = document.getElementById(buy_id);
        block.parentNode.removeChild(block);
    }

    async function acceptBuy(buy_id) {
        let formData = new FormData();
        formData.append('buy_id', buy_id);

        let result = await fetch('/api/sale_advertisement', {
            method: 'POST',
            body: formData
        });

        addNotification(await result.text());
        let block = document.getElementById(buy_id);
        block.parentNode.removeChild(block);
    }
</script>
