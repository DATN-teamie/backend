<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use App\Models\UserInItem;
use App\Models\UserInWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteUserInBoard extends Controller
{
    public function __invoke(Request $request, string $boardId, string $userId)
    {
        $this->deleteUserInItem($boardId, $userId);

        UserInBoard::where('board_id', $boardId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['message' => 'User deleted from board']);
    }

    private function deleteUserInItem($boardId, $userId)
    {
        // Delete from user_in_board
        UserInItem::where('user_id', $userId)
            ->whereHas('item.container.board', function ($query) use (
                $boardId
            ) {
                $query->where('id', $boardId);
            })
            ->delete();
    }
}
