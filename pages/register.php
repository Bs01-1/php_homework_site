<?php

use Classes\Auth;
use Classes\Repositories\UserRepository;
use Classes\Request\AuthRequest;

global $mysqli;

if (isset($_POST['send_reg'])) {
    $request = new AuthRequest($_POST);
    $userRepository = new UserRepository($mysqli);
    $auth = new Auth($request, $userRepository);
    $auth->registration();
}
?>

<head>
    <title>Регистрация</title>
</head>
<body>
<div class="main-wrapper">
    <form method="post" class="register_block_main">
        <span>Регистрация</span>
        <div class="register_block">
            <input required class="small_input" type="text" name="nick" placeholder="Введите никнейм" value="<?=$_SESSION['nick'] ?? null ?>">
            <input required class="small_input" type="password" id="password" name="password" placeholder="Введите пароль"
                   oninput="checkPassword()" value="<?=$_SESSION['password'] ?? null ?>">
            <div class="reg_password_err" id="password_err"></div>
            <input required class="small_input" type="password" id="password_repeat" placeholder="Повторите пароль"
                   oninput="checkPassword()" value="<?=$_SESSION['password'] ?? null ?>">
            <div class="reg_password_err" id="password_err_repeat"></div>
            <input required class="small_input" type="text" name="city" placeholder="Введите ваш город" value="<?=$_SESSION['city'] ?? null ?>">
            <input required class="small_input" type="tel" name="phone" placeholder="Введите ваш номер телефона" value="<?=$_SESSION['phone'] ?? null ?>">
            <div>
                <input required class="small_input" type="date" name="date" value="<?=$_SESSION['date'] ?? null ?>"> Введите вашу дату рождения
            </div>
            <div>
                Выберите ваш пол :
                <input required class="small_input" type="radio" name="sex" value="female"> Женский
                <input required class="small_input" type="radio" name="sex" value="male"> Мужской
            </div>
            <div>
                <input required class="small_input" type="checkbox"> Я прочитал и согласен с пользовательским соглашением
            </div>
            <div class="reg_password_err"><?=$err ?? null ?></div>
            <input class="small_input" id="sumbit_reg" name="send_reg" type="submit" value="Продолжить">
        </div>
    </form>
</div>
</body>