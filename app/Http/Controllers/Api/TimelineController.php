<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Timeline\TimelineAjaxController;
use App\Http\Resources\Timeline\TimelineCollection;
use App\Http\Services\Timeline\Timeline;
use Illuminate\Http\Request;

class TimelineController extends TimelineAjaxController
{

    /**
     * @var Timeline $logicClass
     */
    protected $logicClass = Timeline::class;
    public function index($project, Request $request)
    {
        if (!is_integer($project)) {
            $project = decrypt($project);
        }
        $options = $this->logicClass->getOptions($project);
        $output = [];
        $output['options'] = $options;
        $output['groups'] = $this->logicClass->getGroups($project, false);
        $output['items'] =  $this->logicClass
            ->getItems($project, false, null, null);
        return new TimelineCollection($output);
    }
}
