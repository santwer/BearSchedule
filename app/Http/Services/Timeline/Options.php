<?php

namespace App\Http\Services\Timeline;

use App\Helper\ArrayHelper;
use App\Helper\Handlebars;
use App\Http\Services\BaseService;
use App\Models\ProjectOption;
use Illuminate\Database\Eloquent\Collection;

class Options extends BaseService
{
    protected $options = [];
    protected $isTimeline = true;

    public function __construct(?int $project_id, bool $isTimeline = true)
    {
        $this->isTimeline = $isTimeline;
        $projectOptions = ProjectOption::where('project_id', $project_id)->get();

        $this->options = [
            'editable' => $this->getEdiable($project_id),
            'minHeight' => '550px',
            'groupTemplate' => Handlebars::get('timeline.group.default')
        ];
        foreach($projectOptions as $option) {
            if(method_exists($this, $option->option)) {
                $this->options[$option->option] = call_user_func([$this, $option->option], $option->value);
            } else {
                $this->options[$option->option] = $option->value;
            }
        }
        if(!isset($this->options['template'])) {
            $this->options['template'] = Handlebars::get('timeline.item.standard');
        }
    }

    public function get():array
    {
        return ArrayHelper::dotArrayToMutli($this->options);
    }


    private function template(?string $value):?string {
        if(($value = Handlebars::get($value)) !== null) {
            return $value;
        }
        return null;
    }

    private function getEdiable(int $projectId) {
        $role = $this->getRoleInProject($projectId);

        return [
            'add' => false,
            'remove' => false,
            //$role === 'ADMIN' || $role === 'EDITOR'
            'updateTime' => false,
            'updateGroup' => false,
        ];
    }

    private function getGroupEditable(int $projectId)
    {
        $role = $this->getRoleInProject($projectId);
        return false;
    }

    private function getRoleInProject(int $projectId)
    {
        if(auth()->user() === null) return null;
        $userRole = auth()->user()->projects()->find($projectId);
        if ($userRole->pivot === null) return null;
        return $userRole->pivot->role;
    }
}
