<?php

use Classes\Repositories\UserRepository;
use Classes\Request\AuthRequest;
use Classes\Services\UserService;

global $mysqli;

if (isset($_POST['logout'])){
    session_destroy();
    header("Location: /");
}

if (isset($_POST['send_login'])){
    $request = new AuthRequest($_POST);
    $userRepository = new UserRepository($mysqli);
    $userService = new UserService($userRepository);

    if ($user = $userService->authAttempt($request)){
        $_SESSION['auth'] = $user->token;
        header("Location: /");
    }
    $err = 'Неверный логин или пароль';
}
?>
<div class="main-wrapper">
    <div class="header_box">
        <div class="header_paths">
            <div class="header_path_box">
                <a href="/"><img title="На главную" class="header_logo" src="/img/header/icon.ico" alt=''></a>
                <a href="rentals" class="header_path">Аренда</a>
                <a href="sale" class="header_path">Продажа</a>
            </div>
            <?php if($_SESSION['auth'] ?? null): ?>
                <div class="header_auth_box">
                    <a href="/add_advertisement" class="header_path header_button">Добавить объявление</a>
                    <div class="header_path header_button" onclick="headerViewProfileBox()">...</div>
                    <form method="post" class="header_profile_box">
                        <a class="header_profile_box_text" href="profile<?=$user->id?>">Профиль</a>
                        <label class="header_profile_box_text" for="logout">Выход</label>
                        <input style="display: none" name="logout" id="logout" type="submit">
                    </form>
                </div>
            <?php else: ?>
                <div class="header_auth_box">
                    <a href="/register" class="header_path header_button">Регистрация</a>
                    <div class="header_path header_button login_hover" onclick="headerViewLogInBox()">Вход</div>
                    <form class="header_login_box" method="post">
                        <input class="small_input" type="text" name="nickname" placeholder="Введите никнейм">
                        <input class="small_input" type="password" name="password" placeholder="Введите пароль">
                        <div class="reg_password_err"><?=$err ?? null ?></div>
                        <input class="small_input" type="submit"name="send_login" value="Войти">
                    </form>
                </div>
            <?php endif ?>
        </div>
        <div class="header_img_box">
            <img src="/img/header/headBackground.webp" alt="img not found">
            <div class="header_img_text" id="header_background">Продажа и аренда недвижимости</div>
        </div>
    </div>


