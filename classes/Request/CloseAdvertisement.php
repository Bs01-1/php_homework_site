<?php


namespace Classes\Request;


class CloseAdvertisement extends HttpRequest
{
    public int $user_id;
    public int $advertisement_id;
}