<?php


namespace App\Helper;


use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

class JiraHelper
{
    public static function isEnabled(int $projectId = null): bool
    {
        $enabled = env('ENABLE_JIRA', false);
        if(is_bool($enabled) && $projectId != null) {
            $jiraLogin = ProjectOptionsHelper::get($projectId, [
                'jira_host', 'jira_user', 'jira_password'
            ]);
            if (
                empty($jiraLogin) ||
                empty($jiraLogin['jira_host']) ||
                empty($jiraLogin['jira_user']) ||
                empty($jiraLogin['jira_password'])
            )
                return false;
        }
        if (is_bool($enabled)) {
            return $enabled;
        }
        return false;
    }

    public static function IssueService($project)
    {
        $jiraLogin = ProjectOptionsHelper::get($project, [
            'jira_host', 'jira_user', 'jira_password'
        ]);
        if (
            empty($jiraLogin['jira_host']) ||
            empty($jiraLogin['jira_user']) ||
            empty($jiraLogin['jira_password'])
        ) {
            abort(401);
        }
        return new IssueService(new ArrayConfiguration([
            'jiraHost' => $jiraLogin['jira_host'],
            'jiraUser' => $jiraLogin['jira_user'],
            'jiraPassword' => $jiraLogin['jira_password'],
        ]));
    }

    public static function addIssuesToItems($items)
    {
        $items->load('issues');

        $items->map(function ($item) {
            $avg = self::getIssuesAvg($item->issues);
            $item->process = $avg->process > 100 ? 100 : $avg->process ;
            $item->processExtra = $avg->process > 100 ? 'barred' : '' ;
            $item->processlabel = ($avg->done / 60 / 60).'h / '.($avg->estimate / 60 / 60).'h';
            try {
                if (empty($item->issues)) {
                    $item->jira = collect();
                    return $item;
                }
                $keys = $item->issues->pluck('key')->implode(',');
                $ret = cache()->remember('p_'.$item->project_id.'_item_'.str_replace(',', '_', $keys), 500,
                    function () use ($keys, $item) {
                        $jql = "id IN ({$keys}) order by created DESC";
                        $issueService = JiraHelper::IssueService($item->project_id);
                        return $issueService->search($jql);
                    });
                $item->jira = collect($ret->issues);

            } catch (JiraException $exception) {
                $item->jira = collect();
            }
            return $item;
        });

        return $items;
    }

    public static function getIssuesAvg($issues)
    {
        $result = new \stdClass();
        $result->max = 0;
        $result->totalProcess = 0;
        $result->done = 0;
        $result->estimate = 0;

        foreach ($issues as $issue) {

            $result->max += 100;
            $result->totalProcess += $issue->process;
            $result->done += $issue->done;
            $result->estimate += $issue->estimate;
        }
        if ($result->totalProcess === 0) {
            $result->process = null;
        } else {
            $result->process = round(100 / $result->estimate * $result->done);

        }
        return $result;
    }

    public static function getWorkLogTime(array $keys, int $project)
    {
        $keysIm = implode(',', $keys);
        $keysC = count($keys);
        $jql = "id IN ({$keysIm})";
        $estimate = 0;
        $done = 0;

        foreach ($keys as $key) {
            try {
                $issueService1 = JiraHelper::IssueService($project);
                $issue =  $issueService1->get($key);
                if(isset($issue->fields->timeTracking) && $issue->fields->timeTracking) {
                    $estimate += $issue->fields->timeTracking->originalEstimateSeconds;
                    $done += $issue->fields->timeTracking->timeSpentSeconds;
                }
            } catch (JiraException $exception) {

            }
        }

        $res = new \stdClass();
        $res->estimate = $estimate;
        $res->done = $done;
        $res->process = $estimate === 0 ? 0 : round(100 / $estimate * $done);
        return $res;
    }
}
