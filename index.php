<?php
    session_start();
    require __DIR__.'/models/Auth.php';
?>

<html>
    <head>
        <title>Недвижимость в Екатеринбурге</title>
    </head>
    <body>
        <div class="main-wrapper">
            <? require __DIR__.'/view/header.php' ?>
            <? require __DIR__.'/view/footer.php' ?>
        </div>
    </body>
</html>

