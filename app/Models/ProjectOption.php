<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectOption extends Model
{
    protected $table = 'project_settings';
    protected $fillable = ['project_id', 'option', 'value'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'id', 'project_id');
    }

}
