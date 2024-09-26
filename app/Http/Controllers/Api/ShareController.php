<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\ShareCollection;
use App\Models\Project;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ShareController extends Controller
{
    public function index($project, Request $request)
    {
        if (!is_numeric($project)) {
            $project = decrypt($project);
        }
        $project = Project::with('users')->findOrFail($project);
        if($project->share === null) {
            $project->share = Uuid::uuid4();
            $project->save();
        }

        return new ShareCollection($project->users, $project->share);
    }

    public function saveRole(RoleRequest $request)
    {
        $project = Project::findOrFail($request->projectId());
        if($project->users->contains($request->user)) {
            //if user is last admin, do not update role
            if($request->role !== 'ADMIN'
                && $project->users()->where('user_id', $request->user)->wherePivot('role', 'ADMIN')->count() === 1) {
                return response()->json(['message' => __('Cannot update role, since there need to be one Admin per Project')], 422);
            }

            $project->users()->updateExistingPivot($request->user,
                ['role' => $request->role, 'updated_at' => now()]
            );
            return response()->json(['message' => 'Role updated']);
        }
        $project->users()->attach($request->user,
            ['role' => $request->role, 'created_at' => now(), 'updated_at' => now()]
        );
        return response()->json(['message' => 'Role updated']);
    }

    public function deleteRole(RoleRequest $request)
    {
        $project = Project::findOrFail($request->projectId());
        //if user is last admin, do not update role
        if($request->role !== 'ADMIN'
            && $project->users()->wherePivot('role', 'ADMIN')->where('user_id', $request->user)->count() === 1) {
            return response()->json(['message' => __('Cannot delete role, since there need to be one Admin per Project')], 422);
        }

        $project->users()->detach($request->user);
        return response()->json(['message' => 'Role deleted']);
    }
}
