<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ShareController extends Controller
{
    private $project;

    public function index(Request $request, string $unique)
    {
        if (!$this->checkShare($unique)) {
            return response()->json('No Data', 404);
        }

        $pageTitle = $this->project->name;

        return response()->view('share.index', compact('unique', 'pageTitle'));
    }

    public function getShareJs(Request $request, string $unique)
    {
        if ($this->checkShare($unique)) {
            response()->json('No Data', 404);
        }
        $file = file_get_contents(public_path('js/share.js'));
        $file = str_replace('{$csrf_token}', csrf_token(), $file);
        return response()->make($file, 200, ['content-type' => 'application/javascript']);
    }

    public function getShareCss(Request $request, string $unique)
    {
        if ($this->checkShare($unique)) {
            response()->json('No Data', 404);
        }
        $file = file_get_contents(public_path('css/share.css'));
        return response()->make($file, 200, ['content-type' => 'text/css; charset=UTF-8']);
    }

    public function getData(Request $request, string $unique)
    {
        if($request->header('X-CSRF-TOKEN') !== csrf_token()) {
            response()->json('Not authorizied', 404);
        }
        if ($this->checkShare($unique)) {
            response()->json('No Data', 404);
        }
        $request->request->add(['project' => $this->project->id]);
        $timeline = new TimelineController();
        return $timeline->getData($request);
    }


    private function checkShare(string $unique): bool
    {
        try {
            $uuid = Uuid::fromString($unique)->toString();
        } catch (\Exception $exception) {
            return false;
        }
        $this->project = Project::where('share', $uuid)->first();
        return $this->project !== null;
    }
}
