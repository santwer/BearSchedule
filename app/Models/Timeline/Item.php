<?php

namespace App\Models\Timeline;

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
        'type', 'limitSize', 'editable',
    ];

    public function goups() {
        return $this->belongsTo(Group::class, 'id', 'group');
    }
}
