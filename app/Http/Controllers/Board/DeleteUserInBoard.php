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

        if (!$this->checkPermission($boardId)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage members in this board',
                ],
                403
            );
        }

        UserInBoard::where('board_id', $boardId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['message' => 'User deleted from board']);
    }

    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $member_board_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.member_board_management')
            ->first()->member_board_management;

        return $member_board_management;
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
