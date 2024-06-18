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

class CreateWspRole extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'workspace_id' => 'required|integer|exists:workspaces,id',
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

        if (!$this->checkPermission($validated['workspace_id'])) {
            return response(
                ['message' => 'You do not have permission to create role'],
                403
            );
        }

        $role = WorkspaceRole::create([
            'name' => $validated['name'],
            'workspace_id' => $validated['workspace_id'],
            'create_board' => $validated['create_board'],
            'update_board' => $validated['update_board'],
            'delete_board' => $validated['delete_board'],
            'invite_user' => $validated['invite_user'],
            'remove_user' => $validated['remove_user'],
            'create_role' => $validated['create_role'],
            'update_role' => $validated['update_role'],
            'remove_role' => $validated['remove_role'],
            'assign_role' => $validated['assign_role'],
        ]);

        return response(
            [
                'message' => 'Role created successfully',
                'role' => $role,
            ],
            201
        );
    }
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $create_role = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.create_role')
            ->first()->create_role;

        return $create_role;
    }
}
