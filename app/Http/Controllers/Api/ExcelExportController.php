<?php

namespace App\Http\Controllers\Api;

use App\Exports\GanttExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExcelExportRequest;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller
{
    public function index(Project $project, ExcelExportRequest $request)
    {

        $name = $project->name . '_' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new GanttExport(
                $project,
                $request->startDate(),
                $request->endDate()
            ), $name);
    }
}
