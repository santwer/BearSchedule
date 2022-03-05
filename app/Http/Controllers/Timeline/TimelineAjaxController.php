<?php

namespace App\Http\Controllers\Timeline;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Events\Project\Data;
use App\Helper\Handlebars;
use App\Helper\JiraHelper;
use App\Helper\ModelChangesHelper;
use App\Helper\TimelineHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\Settings\Account;
use App\Http\Services\Timeline\Timeline;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\ProjectOption;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use App\Models\Timeline\ItemLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class TimelineAjaxController extends Controller
{
    /**
     * @var Timeline $logicClass
     */
    protected $logicClass = Timeline::class;

    public function destroyGroup(Request $request)
    {
        if (! $request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        $grp = Group::find($request->get('id'));
        ProjectLog::entry(Actions::DELETE, Types::GROUP, $grp->toJson(), '', auth()->user()->id, $grp->project_id, null,
            $request->get('id'));

        if (! $grp->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        if (TimelineHelper::useWebsocket()) {
            event(new Data($request->get('project_id'), 'GROUP-DELETE', $request->get('id')));
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    public function destroyItem(Request $request)
    {
        if (! $request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        $item = Item::find($request->get('id'));
        ProjectLog::entry(Actions::DELETE, Types::ITEM, $item->toJson(), '', auth()->user()->id, $item->project_id,
            null, $request->get('id'));
        if (! $item->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        if (TimelineHelper::useWebsocket()) {
            event(new Data($request->get('project_id'), 'ITEM-DELETE', $request->get('id')));
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    private function isDynamicLoading(array $options, Request $request)
    {
        return isset($options['displayscale'])
            && ! empty($options['displayscale'])
            && $request->has('start')
            && $request->has('end');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function getData(Request $request, bool $share = false)
    {
        $request->validate([
            'project' => 'required'
        ]);

        $project_id = $request->get('project');
        $options = $this->logicClass->getOptions($project_id);
        $isGetItems = $this->isDynamicLoading($options, $request);

        $only = $request->has('only') ? explode(',', $request->get('only')) : [];

        $output = [];
        $start =  $request->get('start') !== null ? Carbon::parse($request->get('start')) : null;
        $end =  $request->get('end') !== null ? Carbon::parse($request->get('end')) : null;


        if (empty($only) || in_array('options', $only)) {
            $output['options'] = $options;
        }
        if (empty($only) || in_array('groups', $only)) {
            $output['groups'] = $this->logicClass->getGroups($project_id, $share);
        }
        if (empty($only) || in_array('items', $only)) {
            $output['items'] =
                $this->logicClass->getItems($project_id, $share, $start, $end)
                ;
        }

        return response()->timeline($output, 200);
    }

    public function getShareLink(Request $request)
    {
        $request->validate([
            'project' => 'required'
        ]);
        $project = $this->logicClass->getShareLink(
            auth()->user(),
            $request->get('project')
        );
        if ($project === null) {
            return response()->ajax(null, 'Unknown Error', 400);
        }
        ProjectLog::entry(Actions::ADD, Types::SHARE, '{"Share": "Disabled"}', '{"Share": "Active"}',
            auth()->user()->id, $request->get('project'));
        return response()->ajax($project, 'Success', 200);
    }

    public function deleteShareLink(Request $request)
    {
        $request->validate([
            'project' => 'required'
        ]);
        $project_id = $request->get('project');
        $project = auth()->user()->projects()->find($project_id);
        if ($project === null) {
            return response()->ajax(null, 'Unknown Error', 400);
        }
        $project->share = null;
        $project->save();
        ProjectLog::entry(Actions::DELETE, Types::SHARE, '{"Share": "Active"}', '{"Share": "Disabled"}',
            auth()->user()->id, $project_id);
        return response()->ajax(null, 'Success', 200);

    }


    public function setGroup(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'project_id' => 'required|integer'
        ]);
        $request->merge([
            'title' => $request->get('content'),
        ]);

        if (! $request->has('id') || empty($request->get('id'))) {
            $group = new Group;
            $logAction = Actions::ADD;
            $group->visible = true;
        } else {
            $group = Group::find($request->get('id'));
            $logAction = Actions::CHANGE;
        }

        $group = $this->fillModelFillableByRequest($group, $request);
        $values = $group->getRawOriginal();
        if ($group->save()) {
            $hpValues = ModelChangesHelper::getChangedValues($values, $group);
            if ($hpValues !== false) {
                ProjectLog::entry($logAction, Types::GROUP, $hpValues[0], $hpValues[1], auth()->user()->id,
                    $group->project_id, null, $group->id);
            }
            if (TimelineHelper::useWebsocket()) {
                event(new Data($request->get('project_id'), 'GROUP',
                    $this->logicClass->getGroups($request->get('project_id'))->where('id', $group->id)->first()));
            }
            return response()->ajax($group, 'Saved successfully', 200);
        }
        return response()->ajax(null, 'Error can not save.', 400);
    }

    public function setGroupOrder(Request $request)
    {
        $validatedData = $request->validate([
            'groups' => 'required|array',
        ]);
        $groups = collect($validatedData['groups'])->each(function ($item) {
            $grp = Group::find($item['id']);
            if ($grp !== null) {
                $grp->order = $item['order'];
            }
            $grp->save();
        });

        return response()->json('done');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function setItem(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|int',
            'title' => 'required|min:3',
            'group' => 'required_unless:type,=,background',
            'start' => 'required|min:3',
        ]);

        if ($request->get('start') === 'Invalid Date') {
            return response()->ajax(null, 'Start not set.', 422);
        }
        if ($request->has('end') && $request->get('end') !== null && in_array($request->get('type'),
                ['range', 'background'])) {
            $end = $this->convertToDateTime($request->get('end'));
            $start = $this->convertToDateTime($request->get('start'));

            if (! $end->gte($start)) {
                return response()->ajax(null, 'Starts needs to be before End Date', 422);
            }
        }

        if (! $request->has('id') || empty($request->get('id'))) {
            $logAction = Actions::ADD;
            $item = new Item;
        } else {
            $logAction = Actions::CHANGE;
            $item = Item::find($request->get('id'));
        }
        $item = $this->fillModelFillableByRequest($item, $request, ['start', 'end']);

        if ($request->has('color')) {
            if ($request->get('color')['id'] == 'default') {
                $item->style = $this->logicClass->getStyle(null);
            } else {
                if (isset($request->get('color')['style'])) {
                    $item->style = $this->logicClass->getStyle($request->get('color')['style']);
                }
            }
        } else {
            if ($request->filled('style')) {
                $item->style = $request->get('style');
            } else {
                $item->style = $this->logicClass->getStyle(null);
            }
        }
        $values = $item->getRawOriginal();
        if ($item->save()) {
            $hpValues = ModelChangesHelper::getChangedValues($values, $item);
            if ($hpValues !== false) {
                ProjectLog::entry($logAction, Types::ITEM, $hpValues[0], $hpValues[1], auth()->user()->id,
                    $item->project_id, $item->id);
            }
            if ($request->has('links')) {
                $this->logicClass->saveLinks($request->get('links'), $item->id);
            }
            $responseItem = Item::with('links')->find($item->id);
            if (TimelineHelper::useWebsocket()) {
                event(new Data($request->get('project_id'), 'ITEM', $responseItem));
            }
            if ($request->has('jira')) {
                $jiras = $request->get('jira');

                $item->load('issues');
                $issues = $item->issues;
                $deleteIds = $item->issues->whereNotIn('key', $jiras)->pluck('id');
                if ($deleteIds->count() > 0) {
                    Item\Issue::whereIn('id', $deleteIds)->delete();
                }
                $jiras = collect($jiras)->map(function ($key) use ($item) {
                    $newItem = new Item\Issue();
                    $newItem->type = 'JIRA';
                    $newItem->item_id = $item->id;
                    $newItem->key = $key;
                    $log = JiraHelper::getWorkLogTime([$newItem->key], $item->project_id);
                    if ($log->estimate !== 0) {
                        $newItem->estimate = $log->estimate;
                        $newItem->done = $log->done;
                        $newItem->process = $log->process;
                    }
                    return $newItem;
                });


                foreach ($jiras->whereNotIn('key', $issues->pluck('key')) as $i => $newItem) {

                    $newItem->save();

                }

                $avg = JiraHelper::getIssuesAvg($jiras);
                $responseItem->process = $avg->process > 100 ? 100 : $avg->process;
                $responseItem->processExtra = $avg->process > 100 ? 'barred' : '';
                $responseItem->processlabel = ($avg->done / 60 / 60).'h / '.($avg->estimate / 60 / 60).'h';

            } elseif (JiraHelper::isEnabled()) {
                Item\Issue::where('item_id', $item->id)->delete();
            }


            return response()->ajax($responseItem, 'Saved successfully', 200);
        }
        return response()->ajax(null, 'Error can not save.', 400);

    }

    private function convertToDateTime(string $string): Carbon
    {
        $stringParts = explode(' (', $string);
        return Carbon::parse($stringParts[0]);
    }

    private function fillModelFillableByRequest($model, Request $request, $dateTimeCasts = [])
    {
        $casts = $model->getCasts();
        foreach ($model->getFillable() as $fillable) {
            if ($request->has($fillable) && ! empty($request->get($fillable))) {
                if (isset($casts[$fillable]) && $casts[$fillable] === 'integer') {
                    $model->{$fillable} = is_integer($request->get($fillable)) ? $request->get($fillable) : null;
                } else {
                    if (isset($casts[$fillable]) && $casts[$fillable] === 'boolean') {
                        $model->{$fillable} = $request->get($fillable) == "true";
                    } else {
                        if (in_array($fillable, $dateTimeCasts)) {
                            $model->{$fillable} = $this->convertToDateTime($request->get($fillable));
                        } else {
                            $model->{$fillable} = $request->get($fillable);
                        }
                    }
                }
            }
        }
        return $model;
    }

}
