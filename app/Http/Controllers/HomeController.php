<?php

namespace App\Http\Controllers;

use App\Helper\UserHelper;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Services\Dashboard\GraphData;
use App\Http\Services\Settings\Account;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * @var Account $logicClass
     */
    protected $logicClass = Account::class;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $acivity = GraphData::getProjectActivities();
        $allAcivity = GraphData::getProjectActivities(false);

        return view('login.index', compact('acivity', 'allAcivity'));
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
        $this->logicClass->update(
            auth()->user(),
            $validatedData,
            $changePassword);

        return redirect(locale_route('user.settings'));

    }

    public function deleteAccount(Request $request)
    {
        $this->logicClass->delete(auth()->user());
        return (new LoginController())->logout($request);
    }
}
