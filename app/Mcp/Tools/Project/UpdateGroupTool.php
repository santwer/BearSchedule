<?php

namespace App\Mcp\Tools\Project;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Helper\ModelChangesHelper;
use App\Mcp\Concerns\InteractsWithProjectMcp;
use App\Models\ProjectLog;
use App\Models\Timeline\Group;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('update-group')]
#[Description('Update an existing timeline group in the current project.')]
class UpdateGroupTool extends Tool
{
    use InteractsWithProjectMcp;

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'content' => 'nullable|string',
            'title' => 'nullable|string',
            'order' => 'nullable|integer',
            'visible' => 'nullable|boolean',
        ]);

        $project = $this->project();
        $group = $project->groups()->findOrFail($validated['id']);
        $original = $group->getRawOriginal();

        foreach (['content', 'title', 'order', 'visible'] as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] !== null) {
                $group->{$field} = $validated[$field];
            }
        }

        if (isset($validated['content']) && ! isset($validated['title'])) {
            $group->title = $validated['content'];
        }

        $group->save();

        $changes = ModelChangesHelper::getChangedValues($original, $group);

        if ($changes !== false) {
            ProjectLog::entry(
                Actions::CHANGE,
                Types::GROUP,
                $changes[0],
                $changes[1],
                auth()->id(),
                $project->id,
                null,
                $group->id,
            );
        }

        return Response::json($group->fresh()->toArray());
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('Group ID.')->required(),
            'content' => $schema->string()->description('Group label/content.'),
            'title' => $schema->string()->description('Group title.'),
            'order' => $schema->integer()->description('Sort order within the project.'),
            'visible' => $schema->boolean()->description('Whether the group is visible.'),
        ];
    }
}
