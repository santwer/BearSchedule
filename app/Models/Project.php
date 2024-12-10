<?php

namespace App\Models;

use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereUserId(?int $user_id = null)
 */
class Project extends Model
{
    const ROLES = [
        'SUBSCRIBER',
        'ADMIN',
        'EDITOR'
    ];

    protected $fillable = [
        'name',
        'share',
        'archive_date'
    ];

    protected $casts = [
        'archive_date' => 'datetime:Y-m-d',
    ];

    public function getIsArchivedAttribute() : bool
    {
        return $this->archive_date !== null;
    }

    public function scopeWhereUserId($query, ?int $user_id = null)
    {
        $user_id = $user_id ?? auth()->id();
        return $query->whereHas('users', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        });
    }

    public function getEncryptIdAttribute()
    {
        if (config('bearschedule.encrypt_project_id')) {
            return encrypt($this->id);
        }
        return $this->id;
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withPivot([
            'role',
            'updated_at',
            'created_at'
        ]);
    }

    public function apikeys()
    {
        return $this->belongsToMany(ApiKey::class, 'project_api_key');
    }

    public function options()
    {
        return $this->hasMany(ProjectOption::class, 'project_id', 'id');
    }

    public function log()
    {
        return $this->hasMany(ProjectLog::class, 'project_id', 'id');
    }

    public function shareUrl()
    {
        if ($this->share === null) {
            return null;
        }
        else {
            return env('APP_URL') . '/share/' . str_replace('-', '', $this->share) . '/';
        }
    }

    public function option(string $option, ?string $field = null)
    {
        $row = $this->options()->where('option', $option)->first();
        if ($field === null) {
            return $row;
        }
        if ($row === null) {
            return null;
        }
        return $row->{$field};
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'project_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'project_id', 'id')
            ->orderBy('order');
    }
}
