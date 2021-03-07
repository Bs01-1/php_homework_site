<?php

use Classes\Repositories\UserRepository;
use Classes\Services\UserService;
use Classes\User;

    session_start();

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $mysqli = new mysqli('localhost', 'ilya', 'Qdwdqx1233!', 'realty');
    const rootPath = '/var/www/html/php_homework_site';
    require rootPath.'/vendor/autoload.php';
    $foundPage = null;

    $routes = [
        ['url' => '/register', 'path' => 'register.php'],
        ['url' => '/advertisement', 'path' => 'advertisement.php'],
        ['url' => '/', 'path' => 'main.php']
    ];

    $requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'] ?? null;
    if (!$requestUrl) {
        return;
    }

    foreach ($routes as $route){
        if ($route['url'] == $requestUrl){
            $foundPage = $route['path'];
        }
    }

    if (!$foundPage) {
        require rootPath . '/pages/404.php';
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
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?=$title ?></title>
    <link rel="shortout icon" href="/img/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">
    <link rel="stylesheet" href='/style.css'>
</head>
<div class="main-wrapper">
    <?php
        require rootPath . '/pages/header.php';
        require rootPath . '/pages/' . $foundPage;
        require rootPath . '/pages/footer.php';
    ?>
</div>

