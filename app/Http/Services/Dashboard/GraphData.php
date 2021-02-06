<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\BaseService;
use App\Models\ProjectLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class GraphData extends BaseService
{
    public static function getProjectActivities($ownUser = true)
    {
       $projects = auth()->user()->projects->pluck('id');
       $last = Carbon::now()->addMonths(-13);
        $logs = ProjectLog::where(function ($where) use($projects, $ownUser) {
            if($ownUser) {
                $where->whereIn('project_id', $projects);
            }
        })->where('created_at', '>=', $last)
            ->selectRaw('MONTH(created_at) Date, count(DISTINCT id) as amount')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();


        $data = collect();
        $last->addMonth()->month;
        for($i = 1; $i < 13; $i++) {
            $month = $last->addMonth()->month;
           $dt = $logs->where('Date', $month)->first();
           if($dt === null) {
               $data[Lang::get('general.months.'.$month)] = 0;
           } else {
               $data[Lang::get('general.months.'.$month)] = $dt->amount;
           }

        }
        return $data;
    }
}
