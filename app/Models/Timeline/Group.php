<?php

namespace App\Models\Timeline;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $casts = [
        'subgroupStack' => 'array',
        'subgroupVisibility' => 'array',
        'nestedGroups' => 'array',
    ];
    protected $fillable = [
        'title', 'content', 'className', 'style', 'subgroupStack',
        'subgroupVisibility', 'visible', 'treeLevel',
        'showNested', 'project_id', 'parent'
    ];

    public function items() {
        return $this->hasMany(Item::class, 'group', 'id');
    }

    public function nestedGroups() {
        return $this->hasMany(Group::class, 'id', 'parent');
    }
}
