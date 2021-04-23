<?php

namespace App\Http\Controllers\Timeline;

use App\Helper\JiraHelper;
use App\Helper\ProjectOptionsHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\Timeline\Options;
use App\Models\ProjectOption;
use Illuminate\Http\Request;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

class JiraAjaxController extends Controller
{
    public function getIssues(Request $request)
    {
        $request->validate([
            'project' => 'int|required',
            'query' => 'required'
        ]);

        $q = $request->get('query');
        $project = (int) $request->get('project');
        try {
            $jql = "id = '{$q}' || summary ~ '{$q}' order by created DESC";
            $issueService = JiraHelper::IssueService($project);
            $ret = $issueService->search($jql);
        } catch (JiraException $exception) {
            try {
                $jql = "summary ~ '{$q}' order by created DESC";
                $issueService = JiraHelper::IssueService($project);
                $ret = $issueService->search($jql);
            } catch (JiraException $exception) {
                return ["expand" => null, "startAt" => 0, "maxResults" => 15, "total" => 0, "issues" => []];
            }
        }

        return response()->json($ret);

    }


    public function redirectIssue($project, $issue)
    {
        $host = ProjectOption::where('project_id', $project)->where('option', 'jira_host')->first();
        if ($host === null) {
            abort(404);
        }

        return redirect($host->value.'/browse/'.$issue);

    }
}
