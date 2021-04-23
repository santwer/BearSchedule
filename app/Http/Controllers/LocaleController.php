<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLocale(string $lang)
    {
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function redirectMain(Request $request)
    {   $any = $request->route()->uri;
        if(!auth()->check()) {
            $any = '/login';
        }
        return $this->redirect($any);
    }

    public function redirect($any = null)
    {
        $request = \request();
        $this->isLocale($request);
        return redirect(user_locale().'/'.$any);
    }

    private function isLocale(Request $request)
    {
        if(in_array($request->segment(1), config('app.locales'))) {
            abort(404);
        }
    }
}
