<?php

use App\Http\Middleware\ResolveProjectMcpToken;
use App\Mcp\Servers\ProjectServer;
use Illuminate\Support\Facades\Route;
use Laravel\Mcp\Facades\Mcp;

if (! function_exists('mcpOAuthNotSupported')) {
    function mcpOAuthNotSupported(): \Closure
    {
        return static fn () => response()->json([
            'error' => 'OAuth is not supported. Use the project MCP URL with its embedded token.',
        ], 401, [
            'WWW-Authenticate' => 'Bearer realm="mcp", error="invalid_token"',
        ]);
    }
}

/*
| Cursor probes OAuth discovery before connecting. Without these routes the app
| fallback redirects to /{locale}/.well-known/... (HTML), which breaks MCP clients.
*/
Route::get('/.well-known/oauth-authorization-server', mcpOAuthNotSupported())
    ->name('mcp.oauth.authorization-server.stub');

Route::get('/.well-known/oauth-authorization-server/{path}', mcpOAuthNotSupported())
    ->where('path', '.*')
    ->name('mcp.oauth.authorization-server.stub.nested');

Route::get('/.well-known/oauth-protected-resource', mcpOAuthNotSupported())
    ->name('mcp.oauth.protected-resource.stub');

Route::get('/.well-known/oauth-protected-resource/{path}', mcpOAuthNotSupported())
    ->where('path', '.*')
    ->name('mcp.oauth.protected-resource.stub.nested');

Mcp::web('mcp/projects/{token}', ProjectServer::class)
    ->middleware(ResolveProjectMcpToken::class)
    ->where('token', '[A-Za-z0-9_\-]{40,}')
    ->name('mcp.project');
