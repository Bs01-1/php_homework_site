<?php

use Classes\Request\ImgRequest;

if (isset($_POST['send_advertisement']) && $_FILES){
    $request = new ImgRequest($_FILES['imgs']);

}

?>
<body>
    <form method="post" class="advertisement_block_main" enctype="multipart/form-data">
        <span>Добавить объявление</span>
        <div class="advertisement_block">
            <input class="small_input" type="text" name="title" placeholder="Введите заголовок вашего объявления"
                   value="<?=$_SESSION['title'] ?? null ?>">
            <input class="small_input" type="text" name="address" placeholder="Введите адрес недвижимости"
                   value="<?=$_SESSION['address'] ?? null ?>">
            <textarea class="small_input advertisement_textarea" type="text" name="about" onclick="textareaClear(this)"
                ><?=$_SESSION['about'] ?? 'Введите описание недвижимости' ?></textarea>
            <div>
                <input class="small_input" type="radio" name="type" value="sale" id="sale">
                <label for="sale">Продажа</label>
                <input class="small_input" type="radio" name="type" value="purchase" id="purchase">
                <label for="purchase">Покупка</label>
            </div>
            <div class="advertisement_img_block">
                <input multiple style="display: none" type="file" id="advertisement_img" name="imgs[]">
                <label class="small_input" for="advertisement_img">Добавить изображения</label>
                <div class="small_input advertisement_img_btn" onclick="preview()">Показать изображения</div>
            </div>
            <div class="advertisement_img_preview" id="preview"></div>
            <input class="small_input" name="send_advertisement" type="submit" value="Добавить объявление">
        </div>
    </form>
</body>