<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        return view('login.index');
    }

    public function settings()
    {
        $user = auth()->user();
        $isDisabled = $user->uuid !== null;
        return view('login.userSettings', compact('user', 'isDisabled'));
    }
}
