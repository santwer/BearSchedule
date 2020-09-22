<?php

namespace App\Http\Services\Settings;

use App\Http\Services\BaseService;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Account extends BaseService
{
    public function delete(User $user):bool
    {
        $projects = $user->projects()->with('users')->get();
        $deleteProjectIds = [];
        foreach($projects as $project) {
            $gotOtherAdmin = false;
            foreach($project->users as $user) {
                if($user->id != auth()->user()->id && $user->pivot->role === Project::ROLES[1]) {
                    $gotOtherAdmin = true;
                }
            }
            if(!$gotOtherAdmin) {
                $project->users()->sync([]);
                $deleteProjectIds[] = $project->id;
            }
        }

        $deleted =  Project::whereIn('id', $deleteProjectIds)->delete();
        $deletedUser = User::find(auth()->user()->id)->delete();
        return true;
    }

    public function update(?User $user, ?array $data, bool $changePassword = false):?bool
    {
        if($user === null) {
            return false;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];

        if($changePassword) {
            $user->password = Hash::make($data['password']);
        }

       return $user->save();
    }
}
