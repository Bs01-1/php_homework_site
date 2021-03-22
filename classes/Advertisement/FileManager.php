<?php


namespace Classes\Advertisement;


use Classes\Advertisement;
use Classes\Core\Config;
use Classes\Request\ImgRequest;

class FileManager
{
    public function isCorrectFormat(ImgRequest $imgRequest): bool
    {
        $isCorrectFormat = true;
        foreach ($imgRequest->type as $type) {
            if ($type !== 'image/jpeg' && $type !== 'image/webp' && $type !== 'image/png' && $type !== 'image/jpg'){
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

        $imgPath = Config::getInstance();
        $imgPath = rootPath . $imgPath->getByKey('paths.img_advertisement') . '/'
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

    public function getFirstImgPathByAdvertisement(Advertisement $advertisement): string
    {
        $imgPath = Config::getInstance();
        $defaultImgPath = $imgPath->getByKey('paths.img_advertisement');
        $existImgPath = $defaultImgPath . '/' .
            $advertisement->type . '/' . $advertisement->user_id . '/' . $advertisement->id;

        if (!file_exists(rootPath . $existImgPath)){
            return $defaultImgPath . '/img_not_found.jpg';
        }

        $imgDir = scandir(rootPath . $existImgPath);
        if (count($imgDir) <= 2) {
            return $defaultImgPath . '/img_not_found.jpg';
        }

        return $existImgPath . '/' . $imgDir[2];
    }

    public function getCountImagesByAdvertisementId(Advertisement $advertisement): ?int
    {
        $imgPath = Config::getInstance();
        $defaultImgPath = $imgPath->getByKey('paths.img_advertisement');
        $existImgPath = $defaultImgPath . '/' .
            $advertisement->type . '/' . $advertisement->user_id . '/' . $advertisement->id;

        if (!file_exists(rootPath . $existImgPath)){
            return null;
        }

        $imgDir = scandir(rootPath . $existImgPath);
        $imgCount = 0;
        foreach ($imgDir as $item) {
            if ($item !== '.' && $item !== '..') {
                $imgCount += 1;
            }
        }
        return $imgCount;
    }

    public function getImagesPathsByAdvertisementId(Advertisement $advertisement): ?array
    {
        $imgPath = Config::getInstance();
        $defaultImgPath = $imgPath->getByKey('paths.img_advertisement');
        $existImgPath = $defaultImgPath . '/' .
            $advertisement->type . '/' . $advertisement->user_id . '/' . $advertisement->id;

        $imgDir = scandir(rootPath . $existImgPath);
        $imagesPathsArray = [];
        foreach ($imgDir as $item) {
            if ($item !== '.' && $item !== '..') {
                array_push($imagesPathsArray, $item);
            }
        }
        return $imagesPathsArray;
    }
}