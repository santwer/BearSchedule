<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    const ROLES = ['SUBSCRIBER', 'ADMIN', 'EDITOR'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot(['role', 'updated_at', 'created_at']);
    }

    public function options()
    {
        return $this->hasMany(ProjectOption::class, 'project_id', 'id');
    }

    public function option(string $option, ?string $field = null) {
        $row = $this->options()->where('option', $option)->first();
        if($field === null) {
            return $row;
        }
        if($row === null) {
            return null;
        }
        return $row->{$field};
    }
}
