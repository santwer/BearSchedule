<?php

namespace App\Models\Timeline;

use App\Models\ProjectLog;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $casts = [
        'subgroupStack' => 'array',
        'subgroupVisibility' => 'array',
        'nestedGroups' => 'array',
        'show_share' => 'boolean'
    ];
    protected $fillable = [
        'title', 'content', 'className', 'style', 'subgroupStack',
        'subgroupVisibility', 'visible', 'treeLevel',
        'showNested', 'project_id', 'parent', 'show_share'
    ];

    public function items() {
        return $this->hasMany(Item::class, 'group', 'id');
    }

    public function nestedgroups() {
        return $this->hasMany(Group::class, 'parent', 'id');
    }
    public function log()
    {
        return $this->hasMany(ProjectLog::class, 'group_id', 'id');
    }
}
