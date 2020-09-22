<?php

namespace App\Http\Services\Timeline;

use App\Helper\TimelineHelper;
use App\Http\Services\BaseService;

class ItemStyle extends BaseService
{
    /**
     * calls all possible methods by array key
     * @param array|null $styles
     * @return string
     */
    public function get(?array $styles):string
    {
        $returnStyle = [];
        foreach($styles as $method => $style) {
            if(method_exists($this, $method)) {
                $returnStyle[] = call_user_func([$this, $method], $style);
            }
        }
        return implode(' ',$returnStyle);
    }


    private function backgroundColor(string $style):?string
    {
        //add 66 to hex for 40% transparency
        return 'background-color: '.$style.'66;'.
            'border-color: '.TimelineHelper::adjustBrightness($style, -50).';'.
            'color: '.TimelineHelper::getContrastColor($style).';';
    }
}
