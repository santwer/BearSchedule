<?php

namespace App\Http\Services\Timeline;

use App\Helper\ArrayHelper;
use App\Helper\Handlebars;
use App\Http\Services\BaseService;
use App\Models\ProjectOption;
use App\Models\Timeline\Group;
use Illuminate\Database\Eloquent\Collection;

class Options extends BaseService
{
    protected $options = [];
    protected $isTimeline = true;
    protected $role = null;
    protected $ignoreOptions = ['jira_host', 'jira_user', 'jira_password'];

    public function __construct(?int $project_id, bool $isTimeline = true)
    {
        $this->isTimeline = $isTimeline;
        $projectOptions = ProjectOption::where('project_id', $project_id)->get();
        $this->role = $this->getRoleInProject($project_id);

        $this->options = [
            'editable' => $this->getEdiable($project_id),
            'minHeight' => '550px',
            'groupEditable' => $this->getGroupEditable(),
            'groupTemplate' => $this->getGroupTemplate(),
            'groupOrder' => $this->getGroupOrder($project_id),
            'margin.item.vertical' => $this->getGroupMargin()
        ];
        foreach ($projectOptions as $option) {
            if (in_array($option->option, $this->ignoreOptions) && $isTimeline) {
                continue;
            }
            if (method_exists($this, $option->option)) {
                $this->options[$option->option] = call_user_func([$this, $option->option], $option->value);
            } else {
                $this->options[$option->option] = $option->value;
            }
        }
        if (! isset($this->options['template'])) {
            $this->options['template'] = Handlebars::get('timeline.item.standard');
        }
    }

    private function getGroupMargin()
    {
        return 10;
    }

    public function get($option = null): array
    {
        if ($option === null) {
            return ArrayHelper::dotArrayToMutli($this->options);
        }
        if(is_array($option)) {
            $filtered = array_filter(
                $this->options,
                function ($key) use ($option) {
                    return in_array($key, $option);
                },
                ARRAY_FILTER_USE_KEY
            );
            return ArrayHelper::dotArrayToMutli($filtered);
        }
        return isset($this->options[$option]) ? [$this->options[$option]] : [];
    }


    private function template(?string $value): ?string
    {
        if (($value = Handlebars::get($value)) !== null) {
            return $value;
        }
        return null;
    }

    private function getEdiable(int $projectId)
    {
        return [
            'add' => false,
            'remove' => false,
            'updateTime' => ($this->role === 'ADMIN' || $this->role === 'EDITOR'),
            'updateGroup' => ($this->role === 'ADMIN' || $this->role === 'EDITOR'),
        ];
    }

    private function getGroupOrder(int $projectId)
    {
        //groupOrder
        $group = Group::where('project_id', $projectId)->first();
        if ($group === null) {
            return 'id';
        }
        return 'order';
    }

    private function getGroupTemplate()
    {
        if ($this->role === 'ADMIN' || $this->role === 'EDITOR') {
            return Handlebars::get('timeline.group.default');
        }

        return Handlebars::get('timeline.group.show');
    }

    private function getGroupEditable()
    {
        return $this->role === 'ADMIN' || $this->role === 'EDITOR';
    }

    private function getRoleInProject(int $projectId)
    {
        if (auth()->user() === null) {
            return null;
        }
        $userRole = auth()->user()->projects()->find($projectId);
        if ($userRole === null) {
            return null;
        }
        if ($userRole->pivot === null) {
            return null;
        }
        return $userRole->pivot->role;
    }
}
