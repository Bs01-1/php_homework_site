<?php


namespace Classes\Request;


class ImgRequest extends HttpRequest
{
    public array $name = [];
    public array $type = [];
    public array $tmp_name =[];
    public array $error = [];
    public array $size = [];
}