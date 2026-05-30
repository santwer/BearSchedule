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

#[Name('create-item')]
#[Description('Create a new timeline item in the current project.')]
class CreateItemTool extends Tool
{
    use InteractsWithProjectMcp;

    public function handle(Request $request): Response
    {
        $validated = $this->validateItem($request);
        $project = $this->project();
        $timeline = app(Timeline::class);

        $item = new Item;
        $this->fillItem($item, $validated, $project->id, $timeline);
        $item->save();

        ProjectLog::entry(
            Actions::ADD,
            Types::ITEM,
            '',
            json_encode($item->getAttributes()),
            auth()->id(),
            $project->id,
            $item->id,
        );

        return Response::json($item->fresh(['links'])->toArray());
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return $this->itemSchema($schema);
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateItem(Request $request, ?string $itemId = null): array
    {
        $rules = [
            'title' => 'required|string',
            'group' => 'required_unless:type,background|nullable|integer',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
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
        ];

        if ($itemId !== null) {
            $rules['id'] = 'required|uuid';
        }

        return $request->validate($rules);
    }

    /**
     * @return array<string, JsonSchema>
     */
    protected function itemSchema(JsonSchema $schema, bool $includeId = false): array
    {
        $fields = [
            'title' => $schema->string()->description('Item title.')->required(),
            'group' => $schema->integer()->description('Group ID the item belongs to.'),
            'start' => $schema->string()->description('Start date (Y-m-d).')->format('date')->required(),
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
                ->description('Item status. One of: DEFAULT, DELAYED, CRITICAL, TEST, DONE. Defaults to DEFAULT.'),
        ];

        if ($includeId) {
            $fields = ['id' => $schema->string()->description('Item UUID.')->format('uuid')->required()] + $fields;
        }

        return $fields;
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    protected function fillItem(Item $item, array $validated, int $projectId, Timeline $timeline): void
    {
        $item->project_id = $projectId;
        $item->title = $validated['title'];
        $item->start = Carbon::parse($validated['start'])->startOfDay();
        $item->end = isset($validated['end'])
            ? Carbon::parse($validated['end'])->endOfDay()
            : null;

        foreach ([
            'content', 'className', 'align', 'selectable', 'subgroup', 'type',
            'limitSize', 'editable', 'description', 'subtitle', 'group',
        ] as $field) {
            if (array_key_exists($field, $validated)) {
                $item->{$field} = $validated[$field];
            }
        }

        $item->style = $validated['style'] ?? $timeline->getStyle(null);
        $item->status = $validated['status'] ?? ItemStatus::Default;
    }
}
