<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function disclaimer()
    {
        return view('auth.disclaimer', [
            'title' => __('general.disclaimer'),
            'file' => storage_path('app/disclaimer.html')
        ]);
    }

    public function privacy()
    {
        return view('auth.disclaimer', [
            'title' => __('general.privacy_policy'),
            'file' => storage_path('app/privacy.html')
        ]);
    }
}
