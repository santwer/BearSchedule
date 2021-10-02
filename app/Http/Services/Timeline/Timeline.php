<?php

namespace App\Http\Services\Timeline;

use App\Helper\Handlebars;
use App\Helper\JiraHelper;
use App\Helper\TimelineHelper;
use App\Http\Services\BaseService;
use App\Models\ProjectOption;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use App\Models\Timeline\ItemLink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOption\Option;
use Ramsey\Uuid\Uuid;

class Timeline extends BaseService
{
    public function getOptions(int $project_id):array
    {
        $options = new Options($project_id);

        return $options->get();
    }

    public function getItems(int $projectId, bool $share = false, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null)
    {

        $items = Item::where('project_id', $projectId)->orderBy('start')->orderBy('id')
            ->where(function ($where) use($rangeStart, $rangeEnd) {
            if($rangeStart && $rangeEnd) {
                $where->whereBetween('start', [$rangeStart, $rangeEnd]);
                $where->orWhereBetween('end', [$rangeStart, $rangeEnd]);
            }
        });
        if($share) {
            $items = $items->with(['links', 'goups'])
                ->where(function ($where) {
                $where->whereHas('goups', function ($goups) {
                    $goups->where('show_share', 1);
                })->orWhereNull('group');
            })
                ->get();
        } else {
            $items = $items->with('links')->get();
        }
        if(JiraHelper::isEnabled($projectId)) {
            return JiraHelper::addIssuesToItems($items);
        }
        return $items;
    }

    public function getGroups(int $project, bool $share = false)
    {

        $groups = Group::with(['nestedgroups' => function ($nestedGroups) use($share) {
            if($share) {
                $nestedGroups->where('show_share', 1);
            }
        } ])->where('project_id', $project)->where(function ($where) use($share) {
            if($share) {
                $where->where('show_share', true);
            }
        })->orderBy('order')->orderBy('id')->get();
        $order = 0;
        foreach($groups as $i => $group)
        {
            $var = $group->nestedgroups->map(function ($nested) {
                return $nested->id;
            });
            if($group->order === null) {
                $group->order = $order;
            }
            $order = $order + 1;
            unset($group->nestedgroups);
            if (!$var->isEmpty()) $group->nestedGroups = $var;
            $groups[$i] = $group;
        }

        return $groups;
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
