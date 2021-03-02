<?php
    session_start();

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    require __DIR__ . '/vendor/autoload.php';

    $included_files = get_included_files();

    foreach ($included_files as $filename) {
        echo "$filename"."<br>";
    }
?>

<html>
    <head>
        <title>Недвижимость в Екатеринбурге</title>
    </head>
    <body>
        <div class="main-wrapper">
            <? require __DIR__ . '/pages/header.php' ?>
            <? require __DIR__ . '/pages/footer.php' ?>
        </div>
    </body>
</html>

