<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateWspRole extends Controller
{
    public function __invoke(
        Request $request,
        string $workspaceId,
        string $roleWspId
    ) {
        $validated = $request->validate([
            'name' => 'required|string',
            'create_board' => 'required|boolean',
            'update_board' => 'required|boolean',
            'delete_board' => 'required|boolean',
            'invite_user' => 'required|boolean',
            'remove_user' => 'required|boolean',
            'create_role' => 'required|boolean',
            'update_role' => 'required|boolean',
            'remove_role' => 'required|boolean',
            'assign_role' => 'required|boolean',
        ]);

        if (!$this->checkPermission($workspaceId)) {
            return response(
                ['message' => 'You do not have permission to edit role'],
                403
            );
        }

        $role = WorkspaceRole::find($roleWspId);
        $role->name = $validated['name'];
        $role->create_board = $validated['create_board'];
        $role->update_board = $validated['update_board'];
        $role->delete_board = $validated['delete_board'];
        $role->invite_user = $validated['invite_user'];
        $role->remove_user = $validated['remove_user'];
        $role->create_role = $validated['create_role'];
        $role->update_role = $validated['update_role'];
        $role->remove_role = $validated['remove_role'];
        $role->assign_role = $validated['assign_role'];
        $role->save();

        return response(
            [
                'message' => 'Role updated successfully',
            ],
            200
        );
    }
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $update_role = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.update_role')
            ->first()->update_role;

        return $update_role;
    }
}
