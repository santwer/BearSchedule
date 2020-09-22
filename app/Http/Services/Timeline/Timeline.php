<?php

namespace App\Http\Services\Timeline;

use App\Helper\Handlebars;
use App\Helper\TimelineHelper;
use App\Http\Services\BaseService;
use App\Models\ProjectOption;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use App\Models\Timeline\ItemLink;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Timeline extends BaseService
{
    public function getOptions(int $project_id):array
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
        if(!isset($options['template'])) {
            $options['template'] = Handlebars::get('timeline.item.standard');
        }

        return $options;
    }

    public function getItems(int $projectId)
    {
        $items = Item::where('project_id', $projectId)->with('links')->get();
        return $items;
    }

    public function getGroups(int $project)
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

    public function getShareLink(User $user,int $project_id):?string
    {
        $project = $user->projects()->find($project_id);
        if($project === null) {
            return null;
        }
        $project->share = Uuid::uuid4();
        $project->save();
        return $project->shareUrl();
    }

    public function saveLinks(array $links, int $itemId)
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
                $linkItem->title = $link['title'];
                $linkItem->save();
                if (!isset($link['id']) || empty($link['id'])) {
                    $linkItem->items()->attach($itemId);
                }
            } else if (empty($link['href']) && isset($link['id']) && !empty($link['id'])) {
                ItemLink::find($link['id'])->delete();
            }
        }
    }

    public function getStyle(?array $style = null):?string
    {
        if($style === null) {
            return '';
        }
        $styleClass = new ItemStyle();
        return $styleClass->get($style);
    }
}
