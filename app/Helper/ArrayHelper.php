<?php


namespace App\Helper;


class ArrayHelper
{
    public static function dotArrayToMutli(array $array):array
    {
        foreach($array as $key => $item) {
            if(is_array($item)) {
                $array[$key] = self::dotArrayToMutli($item);
            }
            if(strpos($key, '.') !== false) {
                $keyParts = explode('.', $key);
                $array[$keyParts[0]][$keyParts[1]] = $item;
                unset($array[$key]);
            }
        }
        return $array;
    }
}
