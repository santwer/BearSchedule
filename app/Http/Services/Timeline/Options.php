<?php

namespace App\Http\Services\Timeline;

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
            'editable' => false,
            'minHeight' => '550px',
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
        //dd($this->options);
    }

    public function get():array
    {
        return $this->options;
    }


    private function template(?string $value):?string {
        if(($value = Handlebars::get($value)) !== null) {
            return $value;
        }
        return null;
    }
}
