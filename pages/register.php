<?php

use Classes\Repositories\UserRepository;
use Classes\Request\RegisterRequest;
use Classes\Services\UserService;
use Classes\User;

global $mysqli;
/**
 * @var $user User
 */
global $user;

if (isset($user)) {
    return require_once 'pages/404.php';
}

if (isset($_POST['send_reg'])) {
    $request = new RegisterRequest($_POST);
    updateRegisterSession(false, $request);

    $userRepository = new UserRepository($mysqli);
    $userService = new UserService($userRepository);

    if (checkFormData($request, $userService)){
        if ($token = $userService->addNewUser($request)){
            $_SESSION['auth'] = $token;
            updateRegisterSession(true, $request);
            header("Location: /");
        }
    }
}

function checkFormData (RegisterRequest $request, UserService $userService) {
    global $err;

    $pos = mb_stripos($request->date, '-');
    $date = mb_substr($request->date, 0, $pos);

    if (mb_strlen($request->nickname) < 4){
        $err = 'Ваш никнейм слишком короткий! Измените ник и попробуйте снова.';
        return false;
    }
    else if (!$userService->isUniqueNick($request->nickname)){
        $err = 'Такой ник уже есть в нашей базе! Введите другой ник.';
        return false;
    }
    else if (mb_strlen($request->password) < 9){
        $err = 'Ага! Пытаетесь обмануть js? F5 и вводи пароль заново. Хотя тут только проверка на длину, 
                остальные проверки мне тут лень прописывать.';
        return false;
    }
    else if (mb_strlen($request->city) <= 2){
        $err = 'Город где?';
        return false;
    }
    else if ($date > 2002 || $date < 1910){
        $err = 'Ты слишком молод или стар для этого сайта!';
        return false;
    }
    return true;
}

/**
 * @param bool $reloadSession - true - добавить сессии регистрации, false - обнулись сессии.
 * @param RegisterRequest $request
 */
function updateRegisterSession (bool $reloadSession, RegisterRequest $request){
    if (!$reloadSession) {
        $_SESSION['nick'] = $request->nickname;
        $_SESSION['password'] = $request->password;
        $_SESSION['city'] = $request->city;
        $_SESSION['phone'] = $request->phone;
        $_SESSION['date'] = $request->date;
        $_SESSION['sex'] = $request->sex;
    } else {
        $_SESSION['nick'] = null;
        $_SESSION['password'] = null;
        $_SESSION['city'] = null;
        $_SESSION['phone'] = null;
        $_SESSION['date'] = null;
        $_SESSION['sex'] = null;
    }
}
?>
<body>
    <form method="post" class="register_block_main">
        <span>Регистрация</span>
        <div class="register_block">
            <input required class="small_input" type="text" name="nickname" placeholder="Введите никнейм" value="<?=$_SESSION['nick'] ?? null ?>">
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
                <input required class="small_input" type="radio" name="sex" value="female" id="female">
                <label for="female">Женский</label>
                <input required class="small_input" type="radio" name="sex" value="male" id="male">
                <label for="male">Мужской</label>
            </div>
            <div>
                <input required class="small_input" type="checkbox"> Я прочитал и согласен с
                <a href="info#rule">пользовательским соглашением</a>
            </div>
            <div class="reg_password_err"><?=$err ?? null ?></div>
            <input class="small_input" id="sumbit_reg" name="send_reg" type="submit" value="Продолжить">
        </div>
    </form>
</body>