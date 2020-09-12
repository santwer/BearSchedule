<?php


namespace App\Helper;


class Handlebars
{
    public static function get(string $handlebar):?string
    {
        $filepath = str_replace ('.', '/', $handlebar).'.hbs';
        $path = resource_path('handlebars/'.$filepath);
        if(file_exists($path)) {
            return file_get_contents($path);
        }
        return null;
    }
}
