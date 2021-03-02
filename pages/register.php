<?php
    session_start();
    require '../app/Auth.php';

    if (isset($_POST['send_reg'])) {
        $Auth = new \models\Auth($_POST['nick'], $_POST['password'], $_POST['city'], $_POST['phone'], $_POST['date'], $_POST['sex']);
        $err = $Auth->registration();
    }
?>

<head>
    <title>Регистрация</title>
</head>
<body>
<div class="main-wrapper">
    <?  require __DIR__.'\header.php'; ?>
    <form method="post" class="register_block_main">
        <span>Регистрация</span>
        <div class="register_block">
            <input required class="small_input" type="text" name="nick" placeholder="Введите никнейм" value="<?=$_SESSION['nick']?>">
            <input required class="small_input" type="password" id="password" name="password" placeholder="Введите пароль"
                   oninput="checkPassword()" value="<?=$_SESSION['password']?>">
            <div class="reg_password_err" id="password_err"></div>
            <input required class="small_input" type="password" id="password_repeat" placeholder="Повторите пароль"
                   oninput="checkPassword()" value="<?=$_SESSION['password']?>">
            <div class="reg_password_err" id="password_err_repeat"></div>
            <input required class="small_input" type="text" name="city" placeholder="Введите ваш город" value="<?=$_SESSION['city']?>">
            <input required class="small_input" type="tel" name="phone" placeholder="Введите ваш номер телефона" value="<?=$_SESSION['phone']?>">
            <div>
                <input required class="small_input" type="date" name="date" value="<?=$_SESSION['date']?>"> Введите вашу дату рождения
            </div>
            <div>
                Выберите ваш пол :
                <input required class="small_input" type="radio" name="sex" value="female"> Женский
                <input required class="small_input" type="radio" name="sex" value="male"> Мужской
            </div>
            <div>
                <input required class="small_input" type="checkbox"> Я прочитал и согласен с пользовательским соглашением
            </div>
            <div class="reg_password_err"><?=$err;?></div>
            <input class="small_input" id="sumbit_reg" name="send_reg" type="submit" value="Продолжить">
        </div>
    </form>
    <? require __DIR__.'\footer.php'; ?>
</div>
</body>