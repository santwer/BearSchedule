<?php

namespace App\Http\Controllers;

use App\Helper\UserHelper;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function saveSettings(Request $request)
    {
        $validates = [
            'name' => 'required|max:255',
            'email' => 'required|email',
        ];
        $changePassword = false;
        if($request->has('password_confirmation') && !empty($request->get('password_confirmation'))) {
            $validates['password'] = 'min:6|required_with:password_confirmation|same:password_confirmation';
            $validates['password_confirmation'] = 'min:6';
            $changePassword = true;
        }
        $validatedData = $request->validate($validates);

        $user = auth()->user();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if($changePassword) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        return redirect()->route('user.settings');

    }

    public function deleteAccount(Request $request)
    {
        $projects = auth()->user()->projects()->with('users')->get();
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

        return (new LoginController())->logout($request);

    }
}
