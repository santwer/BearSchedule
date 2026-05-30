<?php

namespace Tests\Concerns;

use App\Enums\UserProjectRole;
use App\Models\Project;
use App\Models\ProjectMcpToken;
use App\Models\Timeline\Group;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait CreatesProjectMcpFixtures
{
    protected function createUser(string $email = 'editor@example.com'): User
    {
        return User::query()->create([
            'name' => 'Test User',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);
    }

    protected function createProject(string $name = 'Test Project'): Project
    {
        return Project::query()->create([
            'name' => $name,
        ]);
    }

    protected function attachUserToProject(User $user, Project $project, UserProjectRole $role): void
    {
        $user->projects()->attach($project->id, [
            'role' => $role->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @return array{token: ProjectMcpToken, plain: string}
     */
    protected function createMcpToken(User $user, Project $project, UserProjectRole $role, ?string $name = null): array
    {
        $this->attachUserToProject($user, $project, $role);

        return ProjectMcpToken::createFor($user, $project, $name);
    }

    protected function bindMcpContext(User $user, Project $project, UserProjectRole $role): void
    {
        auth()->setUser($user);
        app()->instance('mcp.project', $project);
        app()->instance('mcp.role', $role);
    }

    protected function createGroup(Project $project, string $content = 'Group A'): Group
    {
        return Group::query()->create([
            'title' => $content,
            'content' => $content,
            'project_id' => $project->id,
            'visible' => true,
            'order' => 0,
        ]);
    }
}
