<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function disclaimer()
    {
        return view('auth.disclaimer');
    }

    public function privacy()
    { // Legal
       // Privacy Policy
        return view('auth.privacy');
    }
}
