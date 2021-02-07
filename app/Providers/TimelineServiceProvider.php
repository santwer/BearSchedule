<?php

namespace App\Providers;

use App\Helper\TimelineHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Database\Eloquent\Collection;

class TimelineServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ResponseFactory $response)
    {
        $response->macro('timeline', function ($value) {
            if(!is_array($value)) {
                throw new \Exception('Not possible to validate');
            }

            foreach($value as $i => $item) {
                if($item instanceof Collection) {
                    $value[$i] = $item->map(function ($entry) {
                        return TimelineHelper::removeNullAttr($entry);
                    });

                } elseif(is_array($item)) {
                    foreach($item as $key => $v) {
                        if($v === null) {
                            unset($value[$i][$key]);
                        }
                    }
                }
            }
            return \response()->json($value);
        });
    }
}
