<?php

namespace App\Http\Controllers\Timeline;

use App\Http\Controllers\Controller;
use App\Models\Timeline\Group;
use App\Models\Timeline\Item;
use Illuminate\Http\Request;

class TimelineAjaxController extends Controller
{

    public function getData(Request $request) {

        $groups = Group::get();
        $items = Item::get();

        $options = [
            'editable' => true,
            'minHeight' => '550px'
        ];

        return response()->timeline([
            'groups' => $groups,
            'items' => $items,
            'options' => $options,
        ], 200);
    }


}
