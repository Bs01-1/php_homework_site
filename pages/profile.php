<?php

use Classes\Repositories\UserRepository;
use Classes\Services\UserService;

global $mysqli;

$requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
$userId = intval(str_replace('/', '', str_replace('profile', '', $requestUrl)));

$userRepository = new UserRepository($mysqli);
$userService = new UserService($userRepository);

if (!$user = $userService->getUserById($userId)) {
    return require_once 'pages/404.php';
}
?>
<div class="profile_main_block">
    <div class="profile_header_block">
        <div>(<?=$user->id?>) Профиль : <?=$user->nickname?> (<?=$user->date?>)</div>
        <div>Дата регистрации : <?=$user->createdAt?></div>
    </div>
    <form method="post" class="profile_header_info">
        <div><p>Город : </p><input class="profile_input" type="text" name="city" value="<?=$user->city?>"></div>
        <div><p>Гендер : </p><input class="profile_input" type="text" name="gender" value="<?=($user->gender === 'male') ? 'Мужской' : 'Женский'?>"></div>
        <div><p>Телефон : </p><input class="profile_input" type="text" name="phone" value="<?=$user->phone?>"></div>
        <input class="profile_input profile_submit" type="submit" value="Сохранить">
    </form>
</div>
