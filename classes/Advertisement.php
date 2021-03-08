<?php


namespace Classes;


use Classes\Request\ImgRequest;

class Advertisement extends Model
{
    public int $id;
    public int $user_id;
    public string $title;
    public string $address;
    public string $about;
    public string $type;

    public function addNewAdvertisementImg (ImgRequest $imgRequest): bool
    {
        if ($imgRequest->name[0] === ''){
            return false;
        }

        $imgPath = rootPath . '/img/advertisement/' . $this->type;

        for ($i = 0; $i < count($imgRequest->name); $i++){
            $path = scandir($imgPath);

            $userDir = false;
            foreach ($path as $item){
                if($item != "." && $item != "..") {
                    if ($item === $this->user_id){
                        $userDir = true;
                    }
                }
            }

            if ($userDir === false) {
                mkdir($imgPath . '/' . $this->user_id);
            }
            $userPath = scandir($imgPath . '/' . $this->user_id);

            $userAdvertisementDir = false;
            foreach ($userPath as $item){
                if($item != "." && $item != "..") {
                    if ($item === $this->id){
                        $userAdvertisementDir = true;
                    }
                }
            }

            if ($userAdvertisementDir === false) {
                mkdir($imgPath . '/' . $this->user_id . '/' . $this->id);
            }

            switch ($imgRequest->type[$i]) {
                case "image/jpeg":
                    $nameNumber = microtime(true) . ".jpeg";
                    break;
                case "image/jpg":
                    $nameNumber = microtime(true) . ".jpg";
                    break;
                case "image/png":
                    $nameNumber = microtime(true) . ".png";
                    break;
                case "image/webp":
                    $nameNumber = microtime(true) . ".webp";
                    break;
            }

            move_uploaded_file($imgRequest->tmp_name[$i], $imgPath . '/' . $this->user_id . '/' . $this->id . '/' . $nameNumber);
        }
        return true;
    }
}