<?php

namespace App\Mcp\Concerns;

use App\Enums\UserProjectRole;
use App\Models\Project;

trait InteractsWithProjectMcp
{
    protected function project(): Project
    {
        return app('mcp.project');
    }

    protected function role(): UserProjectRole
    {
        return app('mcp.role');
    }

    protected function canEdit(): bool
    {
        return in_array($this->role(), [UserProjectRole::ADMIN, UserProjectRole::EDITOR], true);
    }

    public function shouldRegister(): bool
    {
        if (! app()->bound('mcp.role')) {
            return false;
        }

        return $this->canEdit();
    }
}
