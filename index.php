<?php

use Classes\Core\Config;
use Classes\Core\SiteTitle;
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
        ['url' => '/', 'path' => 'main.php'],
        ['url' => '/register', 'path' => 'register.php'],
        ['url' => '/add_advertisement', 'path' => 'addAdvertisement.php'],
        ['url' => '/info', 'path' => 'info.php'],
        ['url' => '/sale', 'path' => 'advertisement.php'],
        ['url' => '/rentals', 'path' => 'advertisement.php'],
        ['url' => '/id(.+)', 'path' => 'id.php'],
        ['url' => '/profile(.+)', 'path' => 'profile.php'],
        ['url' => '/api\/get_advertisement', 'path' => 'api/get_advertisement.php',
            'methods' => ['POST'], 'ajax' => true],
        ['url' => '/api\/set_vote', 'path' => 'api/set_vote.php',
            'methods' => ['POST'], 'ajax' => true],
        ['url' => '/api\/get_images', 'path' => 'api/get_images.php',
            'methods' => ['POST'], 'ajax' => true]
    ];

    $requestUrl = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'] ?? null;
    if (!$requestUrl) {
        return;
    }

    $isAjax = false;
    foreach ($routes as $route){
        if (preg_match($route['url'] . '/', $requestUrl)){
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

    $title = SiteTitle::getTitle($requestUrl);
?>
<?php if (!$isAjax): ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?=$title ?></title>
    <link rel="shortout icon" href="/img/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href='/style.css'>
</head>
<?php endif; ?>

<?php
    if (!$isAjax) {
        require rootPath . '/pages/header.php';
        require rootPath . '/pages/notifications.php';
    }
    require rootPath . '/pages/' . $foundPage;
    if (!$isAjax) {
        require rootPath . '/pages/footer.php';
    }
?>
