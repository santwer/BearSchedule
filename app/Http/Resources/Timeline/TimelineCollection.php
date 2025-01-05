<?php

namespace App\Http\Resources\Timeline;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Gate;
use PhpOption\Option;

/**
 * @see TimelineResource
 */
class TimelineCollection extends ResourceCollection
{
    public static $wrap = null;
    public function toArray(Request $request): array
    {

        $output = ['version' => '1.0.0', 'archived' => $this->collection['archived']];
        if(Gate::allows('adminProject', $this->collection['id'])) {
            $output['isAdmin'] = true;
        }
        if(config('bearschedule.enable_excel')) {
            $output['hasExcel'] = true;
        }
        return [
            'options' => new OptionResource($this->collection['options']),
            'groups' => GroupResource::collection($this->collection['groups']),
            'items' => ItemResource::collection($this->collection['items']),
            'shared_count' => $this->collection['shared_count'],
            'item_templates' => $this->collection['item_templates'],
            'meta' => $output,
        ];
    }
}
