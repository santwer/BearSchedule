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
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tool;

#[Name('delete-group')]
#[Description('Delete a timeline group from the current project.')]
#[IsDestructive]
class DeleteGroupTool extends Tool
{
    use InteractsWithProjectMcp;

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $project = $this->project();
        $group = $project->groups()->findOrFail($validated['id']);

        ProjectLog::entry(
            Actions::DELETE,
            Types::GROUP,
            $group->toJson(),
            '',
            auth()->id(),
            $project->id,
            null,
            $group->id,
        );

        $group->delete();

        return Response::json(['success' => true, 'id' => $validated['id']]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('Group ID.')->required(),
        ];
    }
}
