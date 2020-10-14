<?php

namespace App\Http\Controllers\Timeline;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Helper\Handlebars;
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
        if (!$request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        $grp = Group::find($request->get('id'));
        ProjectLog::entry(Actions::DELETE, Types::GROUP, $grp->toJson(), '', auth()->user()->id, $grp->project_id, null, $request->get('id'));

        if (!$grp->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    public function destroyItem(Request $request)
    {
        if (!$request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        $item = Item::find($request->get('id'));
        ProjectLog::entry(Actions::DELETE, Types::ITEM, $item->toJson(), '', auth()->user()->id, $item->project_id, null, $request->get('id'));

        if (!$item->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getData(Request $request, bool $share = false)
    {
        if (!$request->has('project')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        $project_id = $request->get('project');
        $options = $this->logicClass->getOptions($project_id);

        return response()->timeline([
            'groups' => $this->logicClass->getGroups($project_id, $share),
            'items' => $this->logicClass->getItems($project_id, $share),
            'options' => $options,
        ], 200);
    }

    public function getShareLink(Request $request)
    {
        if (!$request->has('project')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        $project = $this->logicClass->getShareLink(
            auth()->user(),
            $request->get('project')
            );
        if($project === null) {
            return response()->ajax(null, 'Unknown Error', 400);
        }
        ProjectLog::entry(Actions::ADD, Types::SHARE, '{"Share": "Disabled"}', '{"Share": "Active"}', auth()->user()->id, $request->get('project'));
        return response()->ajax($project, 'Success', 200);
    }

    public function deleteShareLink(Request $request)
    {
        if (!$request->has('project')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        $project_id = $request->get('project');
        $project = auth()->user()->projects()->find($project_id);
        if($project === null) {
            return response()->ajax(null, 'Unknown Error', 400);
        }
        $project->share = null;
        $project->save();
        ProjectLog::entry(Actions::DELETE, Types::SHARE, '{"Share": "Active"}', '{"Share": "Disabled"}', auth()->user()->id, $project_id);
        return response()->ajax(null, 'Success', 200);

    }



    public function setGroup(Request $request)
    {
        if (!$request->has('content') || empty($request->get('content'))) {
            return response()->ajax(null, 'Name not set.', 400);
        }
        $request->merge([
            'title' => $request->get('content'),
        ]);
        if (!$request->has('project_id')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        if (!$request->has('id') || empty($request->get('id'))) {
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
            if($hpValues !== false) {
                ProjectLog::entry($logAction, Types::GROUP, $hpValues[0], $hpValues[1], auth()->user()->id, $group->project_id,null,  $group->id);
            }
            return response()->ajax($group, 'Saved successfully', 200);
        }
        return response()->ajax(null, 'Error can not save.', 400);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function setItem(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|int',
            'title' => 'required|min:3',
            'group' => 'required',
            'start' => 'required|min:3',
        ]);
        if ($request->get('start') === 'Invalid Date'
        ) {
            return response()->ajax(null, 'Start not set.', 422);
        }

        if (!$request->has('id') || empty($request->get('id'))) {
            $logAction = Actions::ADD;
            $item = new Item;
        } else {
            $logAction = Actions::CHANGE;
            $item = Item::find($request->get('id'));
        }
        $item = $this->fillModelFillableByRequest($item, $request, ['start', 'end']);

        if ($request->has('color')) {
            if( $request->get('color')['id'] == 'default') {
                $item->style = $this->logicClass->getStyle(null);
            }
            else if(isset($request->get('color')['style'])) {
                $item->style = $this->logicClass->getStyle($request->get('color')['style']);
            }
        } else {
            $item->style = $this->logicClass->getStyle(null);
        }
         $values = $item->getRawOriginal();
        if ($item->save()) {
            $hpValues = ModelChangesHelper::getChangedValues($values, $item);
            if($hpValues !== false) {
                ProjectLog::entry($logAction, Types::ITEM, $hpValues[0], $hpValues[1], auth()->user()->id, $item->project_id, $item->id);
            }
            if ($request->has('links')) {
                $this->logicClass->saveLinks($request->get('links'), $item->id);
            }
            $responseItem = Item::with('links')->find($item->id);
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
            if ($request->has($fillable) && !empty($request->get($fillable))) {
                if (isset($casts[$fillable]) && $casts[$fillable] === 'boolean') {
                    $model->{$fillable} = $request->get($fillable) == "true";
                }
                else if (in_array($fillable, $dateTimeCasts)) {
                    $model->{$fillable} = $this->convertToDateTime($request->get($fillable));
                } else {
                    $model->{$fillable} = $request->get($fillable);
                }
            }
        }
        return $model;
    }

}
