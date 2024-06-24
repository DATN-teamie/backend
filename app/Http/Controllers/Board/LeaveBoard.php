<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\UserInBoard;
use App\Models\UserInItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveBoard extends Controller
{
    public function __invoke(Request $request, string $boardId)
    {
        $userId = Auth::id();

        $board = Board::find($boardId);
        if (!$board) {
            return response()->json(['message' => 'Board not found'], 404);
        }
        if ($board->owner_id == $userId) {
            return response()->json(
                [
                    'message' =>
                        'You are the owner of this board, you cannot leave it',
                ],
                403
            );
        }
        $this->deleteUserInItem($boardId, $userId);

        UserInBoard::where('board_id', $boardId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['message' => 'You left the board']);
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
