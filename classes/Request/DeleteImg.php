<?php


namespace Classes\Request;


class DeleteImg extends HttpRequest
{
    public int $advertisement_id;
    public string $img_path;
}