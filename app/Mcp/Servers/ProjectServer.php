<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Project\CreateGroupTool;
use App\Mcp\Tools\Project\CreateItemTool;
use App\Mcp\Tools\Project\DeleteGroupTool;
use App\Mcp\Tools\Project\DeleteItemTool;
use App\Mcp\Tools\Project\GetProjectTool;
use App\Mcp\Tools\Project\ListGroupsTool;
use App\Mcp\Tools\Project\ListItemsTool;
use App\Mcp\Tools\Project\UpdateGroupTool;
use App\Mcp\Tools\Project\UpdateItemTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('BearSchedule Project')]
#[Version('1.0.0')]
#[Instructions(<<<'MARKDOWN'
    This MCP server provides access to a single BearSchedule project timeline.
    Use list-groups and list-items to read data. Editors and admins can create, update, and delete groups and items.
    All operations are scoped to the project associated with this endpoint.
    MARKDOWN)]
class ProjectServer extends Server
{
    protected array $tools = [
        GetProjectTool::class,
        ListGroupsTool::class,
        ListItemsTool::class,
        CreateGroupTool::class,
        UpdateGroupTool::class,
        DeleteGroupTool::class,
        CreateItemTool::class,
        UpdateItemTool::class,
        DeleteItemTool::class,
    ];
}
