<?php

namespace App\Http\Controllers\Timeline;

use App\Http\Controllers\Controller;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimelineAjaxController extends Controller
{

    public function destroyGroup(Request $request)
    {
        if(!$request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        if(!Group::find($request->get('id'))->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    public function destroyItem(Request $request)
    {
        if(!$request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        if(!Item::find($request->get('id'))->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getData(Request $request)
    {
        if(!$request->has('project')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        $project_id = $request->get('project');

        $groups = Group::where('project_id', $project_id)->get();
        $items = Item::where('project_id', $project_id)->get();

        $options = [
            'editable' => false,
            'minHeight' => '550px'
        ];

        return response()->timeline([
            'groups' => $groups,
            'items' => $items,
            'options' => $options,
        ], 200);
    }

    public function setGroup(Request $request)
    {
        if (!$request->has('title') || empty($request->get('title'))) {
            return response()->ajax(null, 'Title not set.', 400);
        }
        if(!$request->has('project_id')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        if(!$request->has('id') || empty($request->get('id'))) {
            $group= new Group;
            $group->visible = true;
        } else {
            $group = Group::find($request->get('id'));
        }

        $group = $this->fillModelFillableByRequest($group, $request);

        if($group->save()) {

            if($request->has('group') && !empty($nestGrp = $request->get('group'))) {
                $parentGrp = Group::find($nestGrp);
                if($parentGrp !== null) {
                    $parentGrp->nestedGroups[] = $group->id;
                }
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
        if(!$request->has('project_id')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        if (!$request->has('title') || empty($request->get('title'))) {
            return response()->ajax(null, 'Title not set.', 400);
        }
        if (!$request->has('start')
            || empty($request->get('start'))
            || $request->get('start') === 'Invalid Date'
        ) {
            return response()->ajax(null, 'Start not set.', 400);
        }

        if(!$request->has('id') || empty($request->get('id'))) {
            $item = new Item;
        } else {
            $item = Item::find($request->get('id'));
        }
        $item = $this->fillModelFillableByRequest($item, $request, ['start', 'end']);
        if($item->save()) {
            return response()->ajax($item, 'Saved successfully', 200);
        }
        return response()->ajax(null, 'Error can not save.', 400);

    }

    private function convertToDateTime(string $string):Carbon {
        $stringParts = explode(' (', $string);
        return Carbon::parse($stringParts[0]);
    }

    private function fillModelFillableByRequest($model, Request $request, $dateTimeCasts = [])
    {
        foreach ($model->getFillable() as $fillable) {
            if($request->has($fillable) && !empty($request->get($fillable))) {
                if(in_array($fillable, $dateTimeCasts)) {
                    $model->{$fillable} = $this->convertToDateTime($request->get($fillable));
                } else {
                    $model->{$fillable} = $request->get($fillable);
                }
            }
        }
        return $model;
    }

}
