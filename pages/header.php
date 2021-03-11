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


}
?>
<div class="main-wrapper">
    <div class="header_box">
        <div class="header_paths">
            <div class="header_path_box">
                <a href="/"><img title="На главную" class="header_logo" src="/img/header/icon.ico" alt=''></a>
                <div class="header_path"><a href="rentals">Аренда</a></div>
                <div class="header_path"><a href="sale">Продажа</a></div>
            </div>
            <?php if($_SESSION['auth'] ?? null): ?>
                <form class="header_auth_box" method="post">
                    <div class="header_path header_button"><a href="/add_advertisement">Добавить объявление</a></div>
                    <input class="header_path header_button" type="submit" value="Выход" name="logout">
                </form>

            <?php else: ?>
                <div class="header_auth_box">
                    <div class="header_path header_button"><a href="/register">Регистрация</a></div>
                    <div class="header_path header_button login_hover" onclick="headerViewLogInBox()">Вход</div>
                    <form class="header_login_box" method="post">
                        <input class="small_input" type="text" name="nickname" placeholder="Введите никнейм">
                        <input class="small_input" type="password" name="password" placeholder="Введите пароль">
                        <div class="reg_password_err"><?php $err ?? null ?></div>
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


