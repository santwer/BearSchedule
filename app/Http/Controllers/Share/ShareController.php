<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ShareController extends Controller
{
    private $project;

    public function index(Request $request, string $unique)
    {
        if (!$this->checkShare($unique)) {
            throw new HttpException(404);
        }

        $pageTitle = $this->project->name;
        $messages = getTranslatationMessages();

        return response()->view('share.index',
            compact('unique', 'pageTitle', 'messages'));
    }

    public function getShareJs(Request $request, string $unique)
    {
        if ($this->checkShare($unique)) {
            throw new HttpException(404);
        }
        $file = file_get_contents(public_path('js/share.js'));
        $file = str_replace('{$csrf_token}', csrf_token(), $file);
        return response()->make($file, 200, ['content-type' => 'application/javascript']);
    }

    public function getShareCss(Request $request, string $unique)
    {
        if ($this->checkShare($unique)) {
            throw new HttpException(404);
        }
        $file = file_get_contents(public_path('css/share.css'));
        return response()->make($file, 200, ['content-type' => 'text/css; charset=UTF-8']);
    }

    public function getData(Request $request, string $unique)
    {
        if($request->header('X-CSRF-TOKEN') !== csrf_token()) {
            throw new HttpException(401);
        }
        if ($this->checkShare($unique)) {
            throw new HttpException(404);
        }

        $request->merge(['project' => $this->project->id]);
//        $timeline = new TimelineController();
//        return $timeline->getData($this->project->id, $request, true);
        return (new TimelineController())->index($this->project->id, $request, true);
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
