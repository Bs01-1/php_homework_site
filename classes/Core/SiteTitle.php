<?php


namespace Classes\Core;


class SiteTitle
{
    public static function getTitle(string $requestUrl): ?string
    {
        if (preg_match('/api/', $requestUrl)) {
            return null;
        }

        $url = preg_replace('/[0-9].*/', '', $requestUrl);

        $titleString = file_get_contents('title.txt');
        $titlesArray = explode(';', $titleString);

        foreach ($titlesArray as $item) {
            if (preg_match("{$url}/", $item, $matches)) {
                preg_match('/[А-Я].*/', $item, $matches);
                $title = $matches[0];
            }
        }
        return $title ?? 'Страница не найдена';
    }
}