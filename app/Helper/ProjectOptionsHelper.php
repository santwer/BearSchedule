<?php


namespace App\Helper;


use App\Http\Services\Timeline\Options;

class ProjectOptionsHelper
{
    public static function get(int $project_id, $option = null)
    {
        $options = new Options($project_id, false);

        return $options->get($option);
    }
}
