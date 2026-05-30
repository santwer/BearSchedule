<?php

namespace App\Mcp\Tools\Project;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Mcp\Concerns\InteractsWithProjectMcp;
use App\Models\ProjectLog;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;
use Laravel\Mcp\Server\Tool;

#[Name('delete-item')]
#[Description('Delete a timeline item from the current project.')]
#[IsDestructive]
class DeleteItemTool extends Tool
{
    use InteractsWithProjectMcp;

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => 'required|uuid',
        ]);

        $project = $this->project();
        $item = $project->items()->findOrFail($validated['id']);

        ProjectLog::entry(
            Actions::DELETE,
            Types::ITEM,
            $item->toJson(),
            '',
            auth()->id(),
            $project->id,
            $item->id,
        );

        $item->delete();

        return Response::json(['success' => true, 'id' => $validated['id']]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->string()->description('Item UUID.')->format('uuid')->required(),
        ];
    }
}
