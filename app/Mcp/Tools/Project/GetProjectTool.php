<?php

namespace App\Mcp\Tools\Project;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Laravel\Mcp\Server\Tool;

#[Name('get-project')]
#[Description('Get metadata for the current BearSchedule project.')]
#[IsReadOnly]
class GetProjectTool extends Tool
{
    public function handle(Request $request): Response
    {
        $project = app('mcp.project');

        return Response::json([
            'id' => $project->id,
            'name' => $project->name,
            'archived' => $project->is_archived,
            'role' => app('mcp.role')->value,
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
