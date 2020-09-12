<?php

namespace App\Http\Controllers\Timeline;

use App\Helper\Handlebars;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectOption;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use App\Models\Timeline\ItemLink;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimelineAjaxController extends Controller
{

    public function destroyGroup(Request $request)
    {
        if (!$request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        if (!Group::find($request->get('id'))->delete()) {
            return response()->ajax(null, 'Delete not possible', 400);
        }
        return response()->ajax(null, 'Delete successful', 200);
    }

    public function destroyItem(Request $request)
    {
        if (!$request->has('id')) {
            return response()->ajax(null, 'id not set.', 400);
        }
        if (!Item::find($request->get('id'))->delete()) {
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
        if (!$request->has('project')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        $project_id = $request->get('project');
        $options = $this->getOptions($project_id);


        return response()->timeline([
            'groups' => $this->getGroups($project_id),
            'items' => $this->getItems($project_id),
            'options' => $options,
        ], 200);
    }

    private function getOptions(int $project_id):array
    {
        $timelineSettings = ['template'];
        $projectOptions = ProjectOption::where('project_id', $project_id)->whereIn('option', $timelineSettings)->get();
        $options = [
            'editable' => false,
            'minHeight' => '550px',
        ];
        foreach($projectOptions as $option) {
            if($option->option === 'template') {
                if(($value = Handlebars::get($option->value)) !== null) {
                    $options[$option->option] = $value;
                }
                break;
            }
            $options[$option->option] = $option->value;
        }

        return $options;
    }

    private function getItems(int $projectId)
    {
        $items = Item::where('project_id', $projectId)->with('links')->get();
        return $items;
    }

    private function getGroups(int $project)
    {
        return Group::where('project_id', $project)->get()->map(function ($group) {
            $var = $group->nestedgroups->map(function ($nested) {
                return $nested->id;
            });
            unset($group->nestedgroups);
            if (!$var->isEmpty()) $group->nestedGroups = $var;
            return $group;
        });
    }

    public function setGroup(Request $request)
    {
        if (!$request->has('title') || empty($request->get('title'))) {
            return response()->ajax(null, 'Title not set.', 400);
        }
        if (!$request->has('project_id')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        if (!$request->has('id') || empty($request->get('id'))) {
            $group = new Group;
            $group->visible = true;
        } else {
            $group = Group::find($request->get('id'));
        }

        $group = $this->fillModelFillableByRequest($group, $request);

        if ($group->save()) {
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
        if (!$request->has('project_id')) {
            return response()->ajax(null, 'Id not set', 400);
        }
        if (!$request->has('title') || empty($request->get('title'))) {
            return response()->ajax(null, 'Title not set.', 400);
        }
        if (!$request->has('group') || empty($request->get('group'))) {
            return response()->ajax(null, 'Group not set.', 400);
        }
        if (!$request->has('start')
            || empty($request->get('start'))
            || $request->get('start') === 'Invalid Date'
        ) {
            return response()->ajax(null, 'Start not set.', 400);
        }

        if (!$request->has('id') || empty($request->get('id'))) {
            $item = new Item;
        } else {
            $item = Item::find($request->get('id'));
        }
        $item = $this->fillModelFillableByRequest($item, $request, ['start', 'end']);

        if ($item->save()) {
            if ($request->has('links')) {
                $this->saveLinks($request->get('links'), $item->id);
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
        foreach ($model->getFillable() as $fillable) {
            if ($request->has($fillable) && !empty($request->get($fillable))) {
                if (in_array($fillable, $dateTimeCasts)) {
                    $model->{$fillable} = $this->convertToDateTime($request->get($fillable));
                } else {
                    $model->{$fillable} = $request->get($fillable);
                }
            }
        }
        return $model;
    }

    private function saveLinks(array $links, int $itemId)
    {
        foreach ($links as $link) {
            if (isset($link['href']) && !empty($link['href'])) {
                if (!isset($link['title']) || empty($link['href'])) {
                    $link['title'] = $link['href'];
                }
                if (isset($link['id']) && !empty($link['id'])) {
                    $linkItem = ItemLink::find($link['id']);
                } else {
                    $linkItem = new ItemLink;
                }

                $linkItem->href = $link['href'];
                $linkItem->title = $link['href'];
                $linkItem->save();
                if (!isset($link['id']) || empty($link['id'])) {
                    $linkItem->items()->attach($itemId);
                }
            } else if (empty($link['href']) && isset($link['id']) && !empty($link['id'])) {
                ItemLink::find($link['id'])->delete();
            }


        }
    }

}
