<?php

namespace App\Mcp\Tools\Project;

use App\Http\Services\Timeline\Timeline;
use Carbon\Carbon;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Laravel\Mcp\Server\Tool;

#[Name('list-items')]
#[Description('List timeline items in the current project, optionally filtered by date range.')]
#[IsReadOnly]
class ListItemsTool extends Tool
{
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $project = app('mcp.project');
        $timeline = app(Timeline::class);

        $rangeStart = isset($validated['start']) ? Carbon::parse($validated['start']) : null;
        $rangeEnd = isset($validated['end']) ? Carbon::parse($validated['end']) : null;

        return Response::json(
            $timeline->getItems($project->id, false, $rangeStart, $rangeEnd)->values()->all()
        );
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'start' => $schema->string()->description('Optional start date filter (Y-m-d).')->format('date'),
            'end' => $schema->string()->description('Optional end date filter (Y-m-d).')->format('date'),
        ];
    }
}
