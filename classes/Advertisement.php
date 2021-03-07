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

        for ($i = 0; $i < count($imgRequest->name); $i++){
            $path = scandir(rootPath . '/img/advertisement/' . $this->type);
            $nameNumber = 0;
            foreach ($path as $item){
                if($item != "." && $item != "..") $nameNumber += 1;
            }
            switch ($imgRequest->type[$i]) {
                case "image/jpeg":
                    $nameNumber = $this->id. "_" . $this->user_id . "_" . $nameNumber . ".jpeg";
                    break;
                case "image/jpg":
                    $nameNumber = $this->id. "_" . $this->user_id . "_" . $nameNumber . ".jpg";
                    break;
                case "image/png":
                    $nameNumber = $this->id. "_" . $this->user_id . "_" . $nameNumber . ".png";
                    break;
                case "image/webp":
                    $nameNumber = $this->id. "_" . $this->user_id . "_" . $nameNumber . ".webp";
                    break;
            }

            move_uploaded_file($imgRequest->tmp_name[$i], rootPath . '/img/advertisement/' . $this->type . '/' . $nameNumber);
        }
        return true;
    }
}