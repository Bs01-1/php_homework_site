<?php

use Classes\Repositories\UserRepository;
use Classes\Request\ProfileRequest;
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
        if (!$userService->correctPassword($user)) {
            $err = 'Неправильный пароль!';
        } else if (mb_strlen($profileRequest->newPassword) <= 8) {
            $err = 'Такой пароль нельзя';
        }
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
        <input class="profile_input profile_submit" type="submit" name="profile_update" value="Сохранить">
    </form>
</div>
<script type="text/javascript">
    document.title = (document.getElementById('profile')).innerHTML;
</script>
