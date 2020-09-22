<?php

namespace App\Models\Timeline;

use Illuminate\Database\Eloquent\Model;

class ItemLink extends Model
{
    protected $table = 'item_links';

    protected $fillable = ['title', 'href'];

    public function items() {
        return $this->belongsToMany(Item::class, 'item_item_link');
    }
}
