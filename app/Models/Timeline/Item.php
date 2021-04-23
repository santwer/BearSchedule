<?php

namespace App\Models\Timeline;

use App\Models\ProjectLog;
use App\Models\Timeline\Item\Issue;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $casts = [
        'start' => 'datetime:Y-m-d',
        'end' => 'datetime:Y-m-d',
    ];
    protected $fillable = [
        'title', 'content', 'className', 'style', 'align',
        'end', 'start', 'group', 'selectable', 'subgroup',
        'type', 'limitSize', 'editable', 'project_id', 'description', 'subtitle', 'status'
    ];

    public function goups() {
        return $this->belongsTo(Group::class, 'group', 'id');
    }
    public function links() {
        return $this->belongsToMany(ItemLink::class, 'item_item_link');
    }
    public function log()
    {
        return $this->hasMany(ProjectLog::class, 'group_id', 'id');
    }

    public function issues() {
        return $this->hasMany(Issue::class, 'item_id', 'id');
    }
}
