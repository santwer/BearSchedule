<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchUserController extends Controller
{
    public function __invoke($project_id, Request $request)
    {
        return User::ajaxSearch($request->get('query'),$project_id);
    }
}
