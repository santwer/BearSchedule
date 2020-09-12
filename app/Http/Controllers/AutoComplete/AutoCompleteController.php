<?php

namespace App\Http\Controllers\AutoComplete;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AutoCompleteController extends Controller
{
    private $error = "";
    public function index(Request $request, string $controller)
    {
        if(!$request->has('search'))
        {
            return response()->ajax(null, 'no search given', 400);
        }
        if(($result = $this->getModelResult($controller, $request->get('search'))) === null)
        {
            return response()->ajax(null, $this->error, 400);
        }
        return response()->ajax($result, '');
    }

    private function getModelResult(string $model, string $q)
    {
        $base = 'App\\Models\\'.$model;
        if(!class_exists($base)) {
            $this->error = 'Model does not exist';
            return null;
        }
        if(!method_exists($base, 'ajaxSearch')) {
            $this->error = 'Method ajaxSearch(string $q) does not exist in Model';
            return null;
        }
        $result = call_user_func(array($base, 'ajaxSearch'), $q);
        if($result instanceof Collection) {
            return $result;
        }
        $this->error = 'Method ajaxSearch(string $q) does not return instance of Illuminate\Database\Eloquent\Collection';
        return null;
    }
}
