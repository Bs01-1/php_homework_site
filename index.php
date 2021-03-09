<?php

use Classes\Core\Config;
use Classes\Repositories\UserRepository;
use Classes\Services\UserService;

    session_start();

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    const rootPath = '/var/www/html/php_homework_site';
    require rootPath.'/vendor/autoload.php';

    $config = Config::getInstance();
    $mysqli = new mysqli(
            $config->getByKey('mysql.host'),
            $config->getByKey('mysql.name'),
            $config->getByKey('mysql.password'),
            $config->getByKey('mysql.db_name')
    );
    $foundPage = null;

    $routes = [
        ['url' => '/register', 'path' => 'register.php'],
        ['url' => '/add_advertisement', 'path' => 'addAdvertisement.php'],
        ['url' => '/', 'path' => 'main.php'],
        ['url' => '/info', 'path' => 'info.php'],
        ['url' => '/sale', 'path' => 'advertisement.php'],
        ['url' => '/api/get_advertisement', 'path' => 'api/advertisement.php', 'methods' => ['POST'], 'ajax' => true]
    ];

    $requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'] ?? null;
    if (!$requestUrl) {
        return;
    }

    $isAjax = false;
    foreach ($routes as $route){
        if ($route['url'] == $requestUrl){
            $foundPage = $route['path'];
            if (!empty($route['methods']) && !in_array($_SERVER['REQUEST_METHOD'], $route['methods'])) {
                $foundPage = null;
            }
            if (!empty($route['ajax'])) {
                $isAjax = $route['ajax'];
            }
        }
    }

    if (!$foundPage) {
        require rootPath . $config->getByKey('paths.404_page');
        return;
    }

    if (isset($_SESSION['auth'])){
        $userRepositories = new UserRepository($mysqli);
        $userService = new UserService($userRepositories);
        $user = $userService->getUserByToken($_SESSION['auth']);
    }

    $f = fopen('title.txt', 'r+');
    $title = file_get_contents('title.txt');
    fclose($f);
    $pos = mb_strripos($title, $foundPage);
    $title = mb_substr($title, $pos);
    $pos = mb_strpos($title, '=');
    $title = mb_substr($title, $pos + 1);
    $pos = mb_strpos ($title, ';');
    $title = mb_substr($title, 0, $pos);
?>
<?php if (!$isAjax): ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?=$title ?></title>
    <link rel="shortout icon" href="/img/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
    <link rel="stylesheet" href='/style.css'>
</head>
<?php endif; ?>

<?php
    if (!$isAjax) {
        require rootPath . '/pages/header.php';
    }

    require rootPath . '/pages/' . $foundPage;

    if (!$isAjax) {
        require rootPath . '/pages/footer.php';
    }
?>
