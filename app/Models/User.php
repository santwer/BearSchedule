<?php

namespace App\Models;

use App\Helper\UserHelper;
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
        'name', 'email', 'password', 'is_ms_account', 'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects() {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot(['role']);
    }

    public function log()
    {
        return $this->hasMany(ProjectLog::class, 'user_id', 'id');
    }

    public static function ajaxSearch(string $q):Collection
    {
        $domains =  UserHelper::viewableUsers();
        if(empty($domains) || $domains === null) {
            return self::where('email', 'like', $q)
                ->selectRaw("id, name, email, CONCAT(name, ' (', email, ')') as value")
                ->get();
        }
        if(in_array('*', $domains)) {
            return self::where('email', 'like', '%'.$q.'%')->orWhere('name', 'like', '%'.$q.'%')
                ->selectRaw("id, name, email, CONCAT(name, ' (', email, ')') as value")
                ->get();
        }

            return self::where('email', 'like', $q)->orWhere(function ($where) use($domains, $q) {
                foreach ($domains as $domain) {
                    $where->where('email', 'like', '%' . $q . '%@'.$domain);
                }
            })->selectRaw("id, name, email, CONCAT(name, ' (', email, ')') as value")
            ->get();
    }

    public function hasProject($id)
    {
        return $this->projects()->where('project_id', $id)->first() !== null;
    }

    public function getAvatarUrlAttribute() :string
    {
        $hash = md5($this->id);
        return 'https://www.gravatar.com/avatar/' . $hash . '?f=y&d='.env('GRAVATAR_ICON', 'robohash');
    }
}
