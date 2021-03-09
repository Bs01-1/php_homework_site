<?php


namespace Classes\Advertisement;


use Classes\Advertisement;
use Classes\Request\ImgRequest;

class FileManager
{
    public function isCorrectFormat(ImgRequest $imgRequest): bool
    {
        $isCorrectFormat = true;
        foreach ($imgRequest->type as $type) {
            if ($type !== 'image/jpeg' && $type !== 'image/webp' && $type !== 'image/jpeg' && $type !== 'image/jpeg'){
                $isCorrectFormat = false;
            }
        }
        return $isCorrectFormat;
    }

    public function isCorrectSize(ImgRequest $imgRequest)
    {
        $isCorrectSize = true;
        foreach ($imgRequest->size as $size){
            var_dump($size);
            if ($size >= 2097152){
                $isCorrectSize = false;
            }
        }
        return $isCorrectSize;
    }

    public function addNewAdvertisementImg (ImgRequest $imgRequest, Advertisement $advertisement): bool
    {
        if ($imgRequest->name[0] === ''){
            return false;
        }

        $imgPath = rootPath . '/img/advertisement/'
            . $advertisement->type . '/'
            . $advertisement->user_id;

        if (!file_exists($imgPath)) {
            mkdir($imgPath);
        }
        $imgPath = $imgPath . '/' . $advertisement->id;

        if (!file_exists($imgPath)) {
            mkdir($imgPath);
        }
        for ($i = 0; $i < count($imgRequest->name); $i++){

            $nameNumber = '';
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

            move_uploaded_file($imgRequest->tmp_name[$i], $imgPath . '/' . $nameNumber);
        }
        return true;
    }
}