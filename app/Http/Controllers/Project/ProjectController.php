<?php

namespace App\Http\Controllers\Project;

use App\Helper\ModelInfo;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectOption;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProjectController extends Controller
{
    private $viewVariables = [];

    public function index(Request $request, int $project)
    {
        $settings = Project::with('users')->whereHas('users', function (Builder $users) {
            $users->where('users.id', auth()->user()->id);
        })->find($project);
        if($settings === null) {
            return view('login.noproject', $this->viewVariables);
        }
        $pageTitle = $settings->name;

        $groups = Group::where('project_id', $project)->get();
        $items = Item::where('project_id', $project)->get();
        $role = $this->getRoleInProject($project);

        $this->viewVariables = array_merge(compact('project', 'items', 'groups', 'settings', 'role', 'pageTitle'), $this->viewVariables) ;


        return view('login.project', $this->viewVariables);
    }

    public function create(Request $request)
    {

        $project = new Project;
        $project->name = $this->getProjectName();
        $project->save();
        $this->viewVariables['activeTab'] = 'settings';
        $project->users()->attach(auth()->user()->id, ['role' => 'ADMIN']);
        return $this->index($request, $project->id);

    }

    public function update(Request $request, int $project) {
        if($this->getRoleInProject($project) !== 'ADMIN') {
            return $this->index($request, $project);
        }
        $this->viewVariables['activeTab'] = 'settings';

        if($request->has('name')) {
            $name = $request->get('name');
        } else {
            $name = $this->getProjectName();
        }

        $settings = Project::find($project);
        $settings->name = $name;
        $settings->save();
        if($request->has('users')) {
            $this->setUsers($request->get('users'), $project);
        }
        if($request->has('option')) {
            $this->setOptions($request->get('option'), $settings->id);
        }

        return $this->index($request, $project);
    }


    private function getProjectName():string
    {
        $basename = 'Unnamed Project';
        $projects = auth()->user()->projects()->where('name', 'like', $basename.'%')->get();
        if($projects->count() > 0) {
            $basename .= ' ' .($projects->count() + 1);
        }
        return $basename;
    }

    private function setOptions(array $options, int $projectId)
    {
        foreach($options as $option => $value) {
            $entry = ProjectOption::firstOrCreate([ 'project_id' => $projectId, 'option' => $option]);
            $entry->value = $value;
            if($value === null || $value === "null") {
                $entry->delete();
            } else {
                $entry->save();
            }
        }

    }

    private function setUsers($users, int $projectId)
    {
        if($this->getRoleInProject($projectId) !== 'ADMIN') {
            return;
        }
        $project = Project::find($projectId);
        $syncData = [];
        foreach($users as $id => $user)
        {
            if(!isset($user['action']) || $user['action'] !== "delete" || auth()->user()->id === $id)
            {
                $project->users()->detach($id);
                $syncData[$id] = ['role' => $user['role']];
            }
        }

        $project->users()->sync($syncData);
    }

    private function getRoleInProject(int $projectId)
    {
       $userRole = auth()->user()->projects()->find($projectId);
       if($userRole->pivot === null) return null;
       return $userRole->pivot->role;
    }

}
