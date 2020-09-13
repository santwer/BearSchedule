<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $table = 'api_keys';
    protected $keyType = 'string';

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_api_key', 'api_key_id','project_id');
    }
}
