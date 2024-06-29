<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Models\Project;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    public function index(Request $request)
    {


        $projects = Project::whereUserId()
            ->orderBy('name')
            ->get();
        return new ProjectCollection($projects);
    }
}
