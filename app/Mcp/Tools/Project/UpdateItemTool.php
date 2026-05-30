<?php

namespace App\Mcp\Tools\Project;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Enums\ItemStatus;
use App\Helper\ModelChangesHelper;
use App\Http\Services\Timeline\Timeline;
use App\Mcp\Concerns\InteractsWithProjectMcp;
use App\Models\ProjectLog;
use App\Models\Timeline\Item;
use Carbon\Carbon;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('update-item')]
#[Description('Update an existing timeline item in the current project.')]
class UpdateItemTool extends Tool
{
    use InteractsWithProjectMcp;

    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => 'required|uuid',
            'title' => 'nullable|string',
            'group' => 'nullable|integer',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'content' => 'nullable|string',
            'className' => 'nullable|string',
            'style' => 'nullable|string',
            'align' => 'nullable|string',
            'selectable' => 'nullable|boolean',
            'subgroup' => 'nullable|string',
            'type' => 'nullable|string',
            'limitSize' => 'nullable|boolean',
            'editable' => 'nullable|boolean',
            'description' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'status' => ['nullable', Rule::enum(ItemStatus::class)],
        ]);

        $project = $this->project();
        $timeline = app(Timeline::class);
        $item = $project->items()->findOrFail($validated['id']);
        $original = $item->getRawOriginal();

        foreach ([
            'title', 'content', 'className', 'align', 'selectable', 'subgroup', 'type',
            'limitSize', 'editable', 'description', 'subtitle', 'group',
        ] as $field) {
            if (array_key_exists($field, $validated)) {
                $item->{$field} = $validated[$field];
            }
        }

        if (array_key_exists('status', $validated)) {
            $item->status = $validated['status'];
        }

        if (array_key_exists('start', $validated) && $validated['start'] !== null) {
            $item->start = Carbon::parse($validated['start'])->startOfDay();
        }

        if (array_key_exists('end', $validated)) {
            $item->end = $validated['end'] !== null
                ? Carbon::parse($validated['end'])->endOfDay()
                : null;
        }

        if (array_key_exists('style', $validated)) {
            $item->style = $validated['style'] ?? $timeline->getStyle(null);
        }

        $item->save();

        $changes = ModelChangesHelper::getChangedValues($original, $item);

        if ($changes !== false) {
            ProjectLog::entry(
                Actions::CHANGE,
                Types::ITEM,
                $changes[0],
                $changes[1],
                auth()->id(),
                $project->id,
                $item->id,
            );
        }

        return Response::json($item->fresh(['links'])->toArray());
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->string()->description('Item UUID.')->format('uuid')->required(),
            'title' => $schema->string()->description('Item title.'),
            'group' => $schema->integer()->description('Group ID the item belongs to.'),
            'start' => $schema->string()->description('Start date (Y-m-d).')->format('date'),
            'end' => $schema->string()->description('End date (Y-m-d).')->format('date'),
            'content' => $schema->string()->description('Item content.'),
            'className' => $schema->string()->description('CSS class name.'),
            'style' => $schema->string()->description('Inline style.'),
            'align' => $schema->string()->description('Text alignment.'),
            'selectable' => $schema->boolean()->description('Whether the item is selectable.'),
            'subgroup' => $schema->string()->description('Subgroup identifier.'),
            'type' => $schema->string()->description('Item type (e.g. background).'),
            'limitSize' => $schema->boolean()->description('Whether item size is limited.'),
            'editable' => $schema->boolean()->description('Whether the item is editable.'),
            'description' => $schema->string()->description('Item description.'),
            'subtitle' => $schema->string()->description('Item subtitle.'),
            'status' => $schema->string()
                ->description('Item status. One of: DEFAULT, DELAYED, CRITICAL, TEST, DONE.'),
        ];
    }
}
