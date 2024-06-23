<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use App\Models\UserInItem;
use App\Models\UserInWorkspace;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteWspRole extends Controller
{
    public function __invoke(
        Request $request,
        string $workspaceId,
        string $roleId
    ) {
        if (!$this->checkPermission($workspaceId)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to delete role in this workspace',
                ],
                403
            );
        }

        if ($this->checkRoleAssigned($workspaceId, $roleId)) {
            return response(
                [
                    'message' =>
                        'Role is already assigned to user(s) in this workspace',
                ],
                403
            );
        }

        WorkspaceRole::where('id', $roleId)->delete();

        return response()->json(['message' => 'Role deleted from workspace']);
    }
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $remove_role = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.remove_role')
            ->first()->remove_role;

        return $remove_role;
    }

    private function checkRoleAssigned($workspaceId, $roleId)
    {
        $role_assigned = DB::table('user_in_workspace')
            ->where('workspace_id', $workspaceId)
            ->where('workspace_role_id', $roleId)
            ->count();

        if ($role_assigned > 0) {
            return true;
        } else {
            return false;
        }
    }
}
