<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    public static $wrap = '';
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $userArray = [
            'user_name' => $user->name,
            'user_avatar' => $user->avatarUrl,
        ];
        if ($user->isAdmin()) {
            $userArray['is_admin'] = true;
            $userArray['pulse'] = route('pulse');
        }
        return [
            'projects' => $this->collection,
            'user' => $userArray,
            'meta' => [
                'version' => '1.0.0',
            ]
        ];
    }
}
