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

    }
}
