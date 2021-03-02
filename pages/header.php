<?php
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="shortout icon" href="../img/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<div class="header_box">
    <div class="header_paths">
        <div class="header_path_box">
            <a href="../index.php"><img title="На главную" class="header_logo" src="../img/header/icon.ico"></a>
            <div class="header_path">Аренда</div>
            <div class="header_path">Продажа</div>
        </div>
        <?php if(\Classes\Auth::sessionCheck()): ?>
            <div class="header_path header_button"><a href="/advertisement.php">Добавить объявление</a></div>
        <?php else: ?>
            <div class="header_auth_box">
                <div class="header_path header_button"><a href="/register.php">Регистрация</a></div>
                <div class="header_path header_button login_hover">Вход
                    <div class="header_login_box">
                        <input class="small_input" type="text" placeholder="Введите никнейм/номер телефона">
                        <input class="small_input" type="password" placeholder="Введите пароль">
                        <input class="small_input" type="submit" value="Войти">
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="header_img_box">
        <img src="../img/header/headBackground.webp" alt="img not found">
        <div class="header_img_text" id="header_background">Продажа и аренда недвижимости</div>
    </div>
</div>
<script src="../script.js"></script>

