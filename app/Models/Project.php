<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const ROLES = ['SUBSCRIBER', 'ADMIN', 'EDITOR'];
    public function users() {
        return $this->belongsToMany(User::class, 'project_user')->withPivot(['role', 'updated_at', 'created_at']);
    }
}
