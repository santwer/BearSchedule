<?php

namespace App\Http\Controllers\Api;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Helper\Handlebars;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Timeline\TimelineAjaxController;
use App\Http\Resources\Timeline\TimelineCollection;
use App\Http\Services\Timeline\Timeline;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\ProjectOption;
use Illuminate\Http\Request;

class TimelineController extends TimelineAjaxController
{

    /**
     * @var Timeline $logicClass
     */
    protected $logicClass = Timeline::class;

    public function index($project_id, Request $request)
    {
        if (!is_numeric($project_id)) {
            $project_id = decrypt($project_id);
        }
        $project = Project::withCount(['users'])->findOrFail($project_id);

        $options = $this->logicClass->getOptions($project_id);
        $output = [];
        $output['shared_count'] = $project->users_count;
        $output['item_templates'] = [
            [
                'value'    => null,
                'text'     => 'project_itemtype.default',
                'template' => Handlebars::get('timeline.item.standard')
            ],
            [
                'value'    => 'timeline.item.default',
                'text'     => 'project_itemtype.with_subtitle',
                'template' => Handlebars::get('timeline.item.standard')
            ]
        ];
        $output['options'] = $options;
        $output['groups'] = $this->logicClass->getGroups($project_id, false);
        $output['items'] = $this->logicClass
            ->getItems($project_id, false, null, null);
        return new TimelineCollection($output);
    }

    public function settings($project)
    {
        if (!is_numeric($project)) {
            $project = decrypt($project);
        }

        $project = Project::with(['options'])->findOrFail($project);

        return response()->json([
            'data' => [
                'id'   => $project->id,
                'name' => $project->name,
            ]

        ]);
    }

    public function updateSetting($id, Request $request)
    {
        $project = Project::with(['options'])->findOrFail($id);
        if ($request->setting === 'project_name') {
            $old = $project->name;
            $project->name = $request->value;
            if ($project->isDirty()) {
                $project->save();
            }
            if ($old !== $project->name) {
                ProjectLog::entry(Actions::CHANGE, Types::SETTINGS,
                    json_encode(['name' => $old]),
                    json_encode(['name' => $project->name])
                    , auth()->user()->id, $id);
            }
        }
        else {
            $option = $project->options->where('option', $request->setting)->first();
            if ($option) {
                $old = $option->value;
                $option->value = $request->value;
                if ($request->value === null || $request->value === "null") {
                    $option->delete();
                }
                else {
                    $option->save();
                }
                if ($old !== $option->value) {
                    ProjectLog::entry(Actions::CHANGE, Types::SETTINGS,
                        json_encode([$request->setting => $old]),
                        json_encode([$request->setting => $option->value])
                        , auth()->user()->id, $id);
                }
            }
            else {
                $entry = ProjectOption::firstOrCreate([
                    'project_id' => $id,
                    'option'     => $request->setting
                ]);
                if ($request->value === null || $request->value === "null") {
                    $entry->delete();
                }
                else {
                    $entry->value = $request->value;
                    $entry->save();
                }
            }

        }


        return $this->settings($id);
    }
}
