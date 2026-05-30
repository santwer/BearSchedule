<?php

namespace Tests\Feature\Mcp;

use App\DataHelper\ProjectLog\Actions;
use App\DataHelper\ProjectLog\Types;
use App\Enums\ItemStatus;
use App\Enums\UserProjectRole;
use App\Mcp\Servers\ProjectServer;
use App\Mcp\Tools\Project\CreateGroupTool;
use App\Mcp\Tools\Project\CreateItemTool;
use App\Mcp\Tools\Project\ListGroupsTool;
use App\Mcp\Tools\Project\ListItemsTool;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Timeline\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Mcp\Server\Methods\ListTools;
use Laravel\Mcp\Transport\JsonRpcRequest;
use Tests\Concerns\CreatesProjectMcpFixtures;
use Tests\TestCase;

class ProjectMcpToolsTest extends TestCase
{
    use CreatesProjectMcpFixtures;
    use RefreshDatabase;

    public function test_subscriber_only_sees_read_tools(): void
    {
        $user = $this->createUser('subscriber@example.com');
        $project = $this->createProject();
        $this->bindMcpContext($user, $project, UserProjectRole::SUBSCRIBER);

        $tools = $this->listedToolNames();

        $this->assertContains('get-project', $tools);
        $this->assertContains('list-groups', $tools);
        $this->assertContains('list-items', $tools);
        $this->assertNotContains('create-item', $tools);
        $this->assertNotContains('delete-group', $tools);
    }

    public function test_editor_sees_write_tools(): void
    {
        $user = $this->createUser('editor-tools@example.com');
        $project = $this->createProject();
        $this->bindMcpContext($user, $project, UserProjectRole::EDITOR);

        $tools = $this->listedToolNames();

        $this->assertContains('create-item', $tools);
        $this->assertContains('update-group', $tools);
        $this->assertContains('delete-item', $tools);
    }

    public function test_create_item_tool_creates_item_and_project_log(): void
    {
        $user = $this->createUser('creator@example.com');
        $project = $this->createProject();
        $group = $this->createGroup($project);
        $this->bindMcpContext($user, $project, UserProjectRole::EDITOR);

        ProjectServer::tool(CreateItemTool::class, [
            'title' => 'MCP Item',
            'group' => $group->id,
            'start' => '2026-06-01',
            'end' => '2026-06-10',
        ])->assertOk()->assertSee('MCP Item');

        $this->assertDatabaseHas('items', [
            'project_id' => $project->id,
            'title' => 'MCP Item',
            'group' => $group->id,
        ]);

        $item = Item::query()->where('project_id', $project->id)->first();

        $this->assertNotNull($item);
        $this->assertDatabaseHas('project_log', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'item_id' => $item->id,
            'action' => Actions::ADD,
            'type' => Types::ITEM,
        ]);
    }

    public function test_create_item_tool_rejects_invalid_status(): void
    {
        $user = $this->createUser('invalid-status@example.com');
        $project = $this->createProject();
        $group = $this->createGroup($project);
        $this->bindMcpContext($user, $project, UserProjectRole::EDITOR);

        ProjectServer::tool(CreateItemTool::class, [
            'title' => 'Bad Status Item',
            'group' => $group->id,
            'start' => '2026-06-01',
            'status' => 'planned',
        ])->assertHasErrors(['status']);
    }

    public function test_create_item_tool_defaults_status_to_default(): void
    {
        $user = $this->createUser('default-status@example.com');
        $project = $this->createProject();
        $group = $this->createGroup($project);
        $this->bindMcpContext($user, $project, UserProjectRole::EDITOR);

        ProjectServer::tool(CreateItemTool::class, [
            'title' => 'Default Status Item',
            'group' => $group->id,
            'start' => '2026-06-01',
        ])->assertOk();

        $this->assertDatabaseHas('items', [
            'project_id' => $project->id,
            'title' => 'Default Status Item',
            'status' => ItemStatus::Default->value,
        ]);
    }

    public function test_list_items_is_scoped_to_current_project(): void
    {
        $user = $this->createUser('scoped@example.com');
        $projectA = $this->createProject('Project A');
        $projectB = $this->createProject('Project B');
        $groupA = $this->createGroup($projectA, 'Group A');
        $groupB = $this->createGroup($projectB, 'Group B');

        Item::query()->create([
            'title' => 'Item A',
            'project_id' => $projectA->id,
            'group' => $groupA->id,
            'start' => '2026-06-01',
        ]);

        Item::query()->create([
            'title' => 'Item B',
            'project_id' => $projectB->id,
            'group' => $groupB->id,
            'start' => '2026-06-01',
        ]);

        $this->bindMcpContext($user, $projectA, UserProjectRole::SUBSCRIBER);

        ProjectServer::tool(ListItemsTool::class)->assertOk()->assertSee('Item A')->assertDontSee('Item B');
    }

    public function test_create_group_tool_creates_group_in_project(): void
    {
        $user = $this->createUser('group-creator@example.com');
        $project = $this->createProject();
        $this->bindMcpContext($user, $project, UserProjectRole::ADMIN);

        ProjectServer::tool(CreateGroupTool::class, [
            'content' => 'New MCP Group',
        ])->assertOk()->assertSee('New MCP Group');

        $this->assertDatabaseHas('groups', [
            'project_id' => $project->id,
            'content' => 'New MCP Group',
        ]);
    }

    public function test_list_groups_returns_project_groups(): void
    {
        $user = $this->createUser('list-groups@example.com');
        $project = $this->createProject();
        $this->createGroup($project, 'Visible Group');
        $this->bindMcpContext($user, $project, UserProjectRole::SUBSCRIBER);

        ProjectServer::tool(ListGroupsTool::class)->assertOk()->assertSee('Visible Group');
    }

    /**
     * @return array<int, string>
     */
    protected function listedToolNames(): array
    {
        $server = app(ProjectServer::class, ['transport' => new \Laravel\Mcp\Server\Transport\FakeTransporter]);
        $server->start();

        $request = new JsonRpcRequest('test-id', 'tools/list', []);
        $response = app(ListTools::class)->handle($request, $server->createContext());
        $payload = $response->toArray();

        return collect($payload['result']['tools'] ?? [])->pluck('name')->all();
    }
}
