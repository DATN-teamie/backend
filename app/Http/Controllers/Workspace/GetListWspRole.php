<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListWspRole extends Controller
{
    public function __invoke(Request $request, $workspace_id)
    {
        $workspace_roles = WorkspaceRole::where('workspace_id', $workspace_id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response([
            'workspaceRoles' => $workspace_roles,
        ]);
    }
}
