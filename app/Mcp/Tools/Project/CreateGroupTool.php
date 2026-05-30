<?php

namespace App\Mcp\Tools\Project;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Mcp\Concerns\InteractsWithProjectMcp;
use App\Models\ProjectLog;
use App\Models\Timeline\Group;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('create-group')]
#[Description('Create a new timeline group in the current project.')]
class CreateGroupTool extends Tool
{
    use InteractsWithProjectMcp;

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string',
            'order' => 'nullable|integer',
            'visible' => 'nullable|boolean',
        ]);

        $project = $this->project();

        $group = new Group;
        $group->title = $validated['title'] ?? $validated['content'];
        $group->content = $validated['content'];
        $group->project_id = $project->id;
        $group->order = $validated['order'] ?? 0;
        $group->visible = $validated['visible'] ?? true;

        $group->save();

        ProjectLog::entry(
            Actions::ADD,
            Types::GROUP,
            '',
            json_encode($group->getAttributes()),
            auth()->id(),
            $project->id,
            null,
            $group->id,
        );

        return Response::json($group->fresh()->toArray());
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'content' => $schema->string()->description('Group label/content.')->required(),
            'title' => $schema->string()->description('Optional group title (defaults to content).'),
            'order' => $schema->integer()->description('Sort order within the project.'),
            'visible' => $schema->boolean()->description('Whether the group is visible.'),
        ];
    }
}
