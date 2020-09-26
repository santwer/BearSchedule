<?php

namespace App\Models;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model
{
    use HasFactory;
    protected $table = 'project_log';

    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public static function entry(string $action, string $type, string $old, string $new,
                                 int $user_id, int $project_id, ?int $item_id = null, ?int $group_id = null):bool
    {
        try {
            $projectLog = new ProjectLog;
            $projectLog->action = $action;
            $projectLog->type = $type;
            $projectLog->old_value = $old;
            $projectLog->new_value = $new;
            $projectLog->user_id = $user_id;
            $projectLog->project_id = $project_id;
            $projectLog->item_id = $item_id;
            $projectLog->group_id = $group_id;
            return $projectLog->save();
        } catch (\Exception$exception) {
            return false;
        }

    }

}
