<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\UserInWorkspace;
use App\Models\Workspace;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignWspRole extends Controller
{
    public function __invoke(Request $request)
    {
        $valdated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'workspace_id' => 'required|integer|exists:workspaces,id',
            'workspace_role_id' => 'required|integer|exists:workspace_roles,id',
        ]);

        if (!$this->checkPermission($valdated['workspace_id'])) {
            return response(
                ['message' => 'You do not have permission to assign role'],
                403
            );
        }

        $user_in_workspace = UserInWorkspace::where([
            'user_id' => $valdated['user_id'],
            'workspace_id' => $valdated['workspace_id'],
        ])->first();

        if (!$user_in_workspace) {
            return response(['message' => 'User not found'], 404);
        }

        $user_in_workspace->update([
            'workspace_role_id' => $valdated['workspace_role_id'],
        ]);

        return response(
            [
                'message' => 'Workspace Role assigned successfully',
            ],
            200
        );
    }
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $assign_role = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.assign_role')
            ->first()->assign_role;

        return $assign_role;
    }
}
