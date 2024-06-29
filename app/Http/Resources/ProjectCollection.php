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
        return [
            'projects' => $this->collection,
            'user' => [
                'user_name' => $user->name,
                'user_avatar' => $user->avatarUrl,
            ],
            'meta' => [
                'version' => '1.0.0',

            ]
        ];
    }
}
