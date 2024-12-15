<?php

namespace App\Models;

use App\Enums\UserProjectRole;
use App\Helper\UserHelper;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_ms_account',
        'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot(['role']);
    }

    public function hasProjectRole(Project|int $project, UserProjectRole ...$role): bool
    {

        if($project instanceof Project) {
            $project_id = $project->id;
        } else {
            $project_id = $project;
        }
        return $this->projects()->wherePivot('project_id', $project_id)
                ->wherePivotIn('role', array_map(fn($x) => $x->value, $role))->first() !== null;
    }


    public function log()
    {
        return $this->hasMany(ProjectLog::class, 'user_id', 'id');
    }

    public function isAdmin() : bool
    {
        return $this->is_admin;
    }

    public static function ajaxSearch(string $q, $project_id): Collection
    {
        $domains = UserHelper::viewableUsers();

        if (empty($domains) || $domains === null) {
            return self::where('email', 'like', $q)
                ->whereDoesntHave('projects', function ($query) use ($project_id) {
                    $query->where('project_id', $project_id);
                })
                ->selectRaw("id, name, email, CONCAT(name, ' (', email, ')') as value")
                ->get();
        }
        if (in_array('*', $domains)) {
            return self::where('email', 'like', '%' . $q . '%')->orWhere('name', 'like', '%' . $q . '%')
                ->whereDoesntHave('projects', function ($query) use ($project_id) {
                    $query->where('project_id', $project_id);
                })
                ->selectRaw("id, name, email, CONCAT(name, ' (', email, ')') as value")
                ->get();
        }

        return self::where('email', 'like', $q)->where(function ($where) use ($domains, $q) {
            foreach ($domains as $domain) {
                $where->orWhere('email', 'like', '%' . $q . '%@' . $domain);
            }
        })
            ->whereDoesntHave('projects', function ($query) use ($project_id) {
                $query->where('project_id', $project_id);
            })
            ->selectRaw("id, name, email, CONCAT(name, ' (', email, ')') as value")
            ->get();
    }

    public function hasProject($id)
    {
        return $this->projects()->where('project_id', $id)->first() !== null;
    }

    public function getAvatarUrlAttribute(): string
    {
        $hash = md5($this->id);
        return 'https://www.gravatar.com/avatar/' . $hash . '?f=y&d=' . env('GRAVATAR_ICON', 'robohash');
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
