<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class AjaxResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ResponseFactory $response)
    {
        $response->macro('ajax', function ($value, ?string $message = null, int $status = 200) {
            return response()->json([
                'status' => $status === 200 ? 'SUCCESS' : 'ERROR',
                'data' => $value,
                'message' => $message
            ], $status);
        });
    }
}
