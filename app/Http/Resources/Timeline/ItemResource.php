<?php

namespace App\Http\Resources\Timeline;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        //dd($this->resource);
        return [
            'id'          => $this->id,
            'content'     => $this->content,
            'title'       => $this->title,
            'className'   => $this->className,
            'style'       => $this->style,
            'align'       => $this->align,
            'group'       => $this->group,
            'selectable'  => $this->selectable,
            'subgroup'    => $this->subgroup,
            'type'        => $this->type,
            'limitSize'   => $this->limitSize,
            'editable'    => $this->editable,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'project_id'  => $this->project_id,
            'description' => $this->description,
            'subtitle'    => $this->subtitle,
            'status'      => $this->status,
            'is_series'   => $this->is_series,
            'series'      => $this->series,
            'start'       => $this->start->format('Y-m-d H:i:s'),
            'end'         => $this->end->endOfDay()->format('Y-m-d H:i:s'),
        ];
    }

}
