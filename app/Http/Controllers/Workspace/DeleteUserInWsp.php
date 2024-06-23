<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use App\Models\UserInItem;
use App\Models\UserInWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteUserInWsp extends Controller
{
    public function __invoke(
        Request $request,
        string $workspaceId,
        string $userId
    ) {
        if (!$this->checkPermission($workspaceId)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to delete user in this workspace',
                ],
                403
            );
        }

        $this->deleteUserInBoardAndItem($workspaceId, $userId);

        UserInWorkspace::where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['message' => 'User deleted from workspace']);
    }
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $remove_user = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.remove_user')
            ->first()->remove_user;

        return $remove_user;
    }
    private function deleteUserInBoardAndItem($workspaceId, $userId)
    {
        // Delete from user_in_board
        UserInBoard::where('user_id', $userId)
            ->whereHas('board', function ($query) use ($workspaceId) {
                $query->where('workspace_id', $workspaceId);
            })
            ->delete();

        // Delete from user_in_item
        UserInItem::where('user_id', $userId)
            ->whereHas('item.container.board', function ($query) use (
                $workspaceId
            ) {
                $query->where('workspace_id', $workspaceId);
            })
            ->delete();
    }
}
