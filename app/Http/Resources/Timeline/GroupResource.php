<?php

namespace App\Http\Resources\Timeline;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id'                 => $this->id,
            'content'            => $this->content,
            'title'              => $this->title,
            'project_id'         => $this->project_id,
            'parent'             => $this->parent,
            'className'          => $this->className,
            'style'              => $this->style,
            'subgroupStack'      => $this->subgroupStack,
            'subgroupVisibility' => $this->subgroupVisibility,
            'visible'            => $this->visible,
            'treeLevel'          => $this->treeLevel,
            'showNested'         => $this->showNested,
            'show_share'         => $this->show_share,
            'order'              => $this->order,
            'subproject'         => $this->subproject,
            'has_subproject'     => $this->has_subproject,
            'nestedGroups'       => $this->nestedGroups,

        ];
    }
}
