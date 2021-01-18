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
        $file = self::getLastUpdate($file);
        return $file;
    }

    private static function getLastUpdate(string $updateText):string
    {
        $str = [];
        $parts = explode('
## ', $updateText);
        $str[] = $parts[0];
        for($i = 1; sizeof($parts) > $i; $i++) {
            if(!str_starts_with($parts[$i], '[Unreleased]')) {
                $str[] = '## '.$parts[$i];
            }
        }
        return implode('', $str);
    }
}
