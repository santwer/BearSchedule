<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->encrypt_id,
            'name'       => $this->name,
            'share'      => $this->share,
            'created_at' => $this->created_at,
            'is_archived' => $this->is_archived,
            'collaboration' => $this->users_count,
        ];
    }
}
