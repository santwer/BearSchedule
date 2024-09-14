<?php

namespace App\Http\Resources\Timeline;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use PhpOption\Option;

/**
 * @see TimelineResource
 */
class TimelineCollection extends ResourceCollection
{
    public static $wrap = null;
    public function toArray(Request $request): array
    {
        return [
            'options' => new OptionResource($this->collection['options']),
            'groups' => GroupResource::collection($this->collection['groups']),
            'items' => ItemResource::collection($this->collection['items']),
        ];
    }
}
