<?php

namespace App\Http\Controllers\Project;

use App\Helper\ModelInfo;
use App\Http\Controllers\Controller;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request, int $project) {
        $groups = Group::where('project_id', $project)->get();
        $items = Item::where('project_id', $project)->get();

        //dd(ModelInfo::getFillables(Item::class));

        return view('login.project', compact('project', 'items', 'groups'));
    }
}
