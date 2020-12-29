<?php


namespace App\Helper;



use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\File;

class ChangelogHelper
{
    public static function getHtml()
    {
       $file = self::getFile();

        return Markdown::parse($file);
    }

    private static function getFile()
    {
        $file = File::get(base_path('CHANGELOG.md'));
        $file = str_replace('# Changelog', '', $file);
        return $file;
    }
}
