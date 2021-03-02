<?php
    session_start();

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $mysqli = new mysqli('localhost', 'root', 'root', 'realty');
    const rootPath = '/var/www/html/php_homework_site';
    require rootPath.'/vendor/autoload.php';
    $foundPage = null;

    $routes = [
        ['url' => '/register', 'path' => 'register.php'],
        ['url' => '/advertisement', 'path' => 'advertisement.php'],
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

    require rootPath . '/pages/header.php';
    require rootPath . '/pages/' . $foundPage;
    require rootPath . '/pages/footer.php';
?>

