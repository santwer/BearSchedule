<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsRequest;
use App\Http\Resources\SettingsResource;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return new SettingsResource($request->user());
    }

    public function update(SettingsRequest $request)
    {
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return new SettingsResource($user);
    }
}
