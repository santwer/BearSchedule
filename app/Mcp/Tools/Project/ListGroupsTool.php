<?php

namespace App\Mcp\Tools\Project;

use App\Http\Services\Timeline\Timeline;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Laravel\Mcp\Server\Tool;

#[Name('list-groups')]
#[Description('List all timeline groups in the current project.')]
#[IsReadOnly]
class ListGroupsTool extends Tool
{
    public function handle(Request $request): Response
    {
        $project = app('mcp.project');
        $timeline = app(Timeline::class);

        return Response::json($timeline->getGroups($project->id)->values()->all());
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
