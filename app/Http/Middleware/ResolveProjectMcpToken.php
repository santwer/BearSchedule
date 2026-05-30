<?php

namespace App\Http\Middleware;

use App\Enums\UserProjectRole;
use App\Models\ProjectMcpToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveProjectMcpToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->route('token');

        if (! is_string($plainToken) || $plainToken === '') {
            abort(404);
        }

        $token = ProjectMcpToken::findByPlainToken($plainToken);

        if ($token === null) {
            abort(404);
        }

        $token->loadMissing(['user', 'project']);

        if (! $token->user->hasProjectRole($token->project, UserProjectRole::ADMIN, UserProjectRole::EDITOR, UserProjectRole::SUBSCRIBER)) {
            abort(404);
        }

        $membership = $token->user->projects()
            ->where('projects.id', $token->project_id)
            ->first();

        if ($membership === null) {
            abort(404);
        }

        auth()->setUser($token->user);

        app()->instance('mcp.project', $token->project);
        app()->instance('mcp.role', UserProjectRole::from($membership->pivot->role));
        app()->instance('mcp.token', $token);

        $token->markUsed();

        return $next($request);
    }
}
