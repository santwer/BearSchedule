<?php

namespace Tests\Feature\Mcp;

use App\Models\ProjectMcpToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Concerns\CreatesProjectMcpFixtures;
use Tests\TestCase;

class ProjectMcpAuthTest extends TestCase
{
    use CreatesProjectMcpFixtures;
    use RefreshDatabase;

    public function test_unknown_token_returns_not_found(): void
    {
        $response = $this->postJson('/mcp/projects/00000000_invalidtoken_abcdefghijklmnopqrstuvwxyz1234567890', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertNotFound();
    }

    public function test_revoked_token_returns_not_found(): void
    {
        $user = $this->createUser();
        $project = $this->createProject();
        $created = $this->createMcpToken($user, $project, \App\Enums\UserProjectRole::EDITOR);
        $created['token']->revoke();

        $response = $this->postJson('/mcp/projects/'.$created['plain'], [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertNotFound();
    }

    public function test_expired_token_returns_not_found(): void
    {
        $user = $this->createUser('expired@example.com');
        $project = $this->createProject('Expired Project');
        $this->attachUserToProject($user, $project, \App\Enums\UserProjectRole::EDITOR);

        $generated = ProjectMcpToken::generatePlainToken();
        ProjectMcpToken::query()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'token_hash' => $generated['hash'],
            'token_prefix' => $generated['prefix'],
            'expires_at' => Carbon::now()->subMinute(),
        ]);

        $response = $this->postJson('/mcp/projects/'.$generated['plain'], [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertNotFound();
    }

    public function test_valid_token_allows_mcp_request(): void
    {
        $user = $this->createUser('valid@example.com');
        $project = $this->createProject('Valid Project');
        $created = $this->createMcpToken($user, $project, \App\Enums\UserProjectRole::EDITOR);

        $response = $this->postJson('/mcp/projects/'.$created['plain'], [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => [],
        ]);

        $response->assertOk();
        $response->assertJsonPath('result.tools.0.name', 'get-project');
    }
}
