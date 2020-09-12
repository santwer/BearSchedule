<?php


namespace App\Helper;


class ModelInfo
{
    static public function getFillables(string $model)
    {
        if(!class_exists($model)) {
            return [];
        }
        $item = new $model;
        if(!method_exists($item, 'getFillable')) {
            return [];
        }
        return collect($item->getFillable());
    }
}
