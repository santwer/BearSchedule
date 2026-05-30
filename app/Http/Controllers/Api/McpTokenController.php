<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMcpToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class McpTokenController extends Controller
{
    public function index(string $project): JsonResponse
    {
        $projectId = $this->resolveProjectId($project);
        $projectModel = Project::findOrFail($projectId);

        if (! Gate::allows('viewProject', $projectModel)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tokens = ProjectMcpToken::query()
            ->active()
            ->where('project_id', $projectId)
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'token_prefix', 'last_used_at', 'expires_at', 'created_at']);

        return response()->json(['data' => $tokens]);
    }

    public function store(string $project, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $projectId = $this->resolveProjectId($project);
        $projectModel = Project::findOrFail($projectId);

        if (! Gate::allows('viewProject', $projectModel)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $created = ProjectMcpToken::createFor(
            auth()->user(),
            $projectModel,
            $validated['name'] ?? null,
            isset($validated['expires_at']) ? \Illuminate\Support\Carbon::parse($validated['expires_at']) : null,
        );

        return response()->json([
            'data' => [
                'id' => $created['token']->id,
                'name' => $created['token']->name,
                'token_prefix' => $created['token']->token_prefix,
                'url' => url(route('mcp.project', ['token' => $created['plain']], absolute: false)),
                'expires_at' => $created['token']->expires_at,
                'created_at' => $created['token']->created_at,
            ],
        ], 201);
    }

    public function destroy(string $project, int $tokenId): JsonResponse
    {
        $projectId = $this->resolveProjectId($project);
        $projectModel = Project::findOrFail($projectId);

        if (! Gate::allows('viewProject', $projectModel)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = ProjectMcpToken::query()
            ->where('project_id', $projectId)
            ->where('user_id', auth()->id())
            ->findOrFail($tokenId);

        $token->revoke();

        return response()->json(['success' => true]);
    }

    protected function resolveProjectId(string $project): int
    {
        if (! is_numeric($project)) {
            return (int) decrypt($project);
        }

        return (int) $project;
    }
}
