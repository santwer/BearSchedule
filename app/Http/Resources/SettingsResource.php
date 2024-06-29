<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'created_at'    => $this->created_at->format(localeDateFormat(true)),
            'is_ms_account' => (bool)$this->is_ms_account,
            'uuid'          => $this->uuid,
            'avatar'        => $this->avatarUrl,
        ];
    }
}
