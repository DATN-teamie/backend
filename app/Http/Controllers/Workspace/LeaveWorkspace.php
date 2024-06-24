<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use App\Models\UserInItem;
use App\Models\UserInWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveWorkspace extends Controller
{
    public function __invoke(Request $request, string $workspaceId)
    {
        $userId = Auth::id();

        $workspace = Workspace::find($workspaceId);
        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }
        if ($workspace->owner_id == $userId) {
            return response(
                [
                    'message' =>
                        'You are the owner of this workspace, you cannot leave it',
                ],
                403
            );
        }

        $this->deleteUserInBoardAndItem($workspaceId, $userId);

        UserInWorkspace::where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['message' => 'You left the workspace']);
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
