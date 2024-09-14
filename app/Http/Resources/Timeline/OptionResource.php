<?php

namespace App\Http\Resources\Timeline;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource;
    }
}
