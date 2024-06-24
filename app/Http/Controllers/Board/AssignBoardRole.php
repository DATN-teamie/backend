<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignBoardRole extends Controller
{
    public function __invoke(Request $request)
    {
        $valdated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'board_id' => 'required|integer|exists:boards,id',
            'board_role_id' => 'required|integer|exists:board_roles,id',
        ]);

        if (!$this->checkPermission($valdated['board_id'])) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage roles in this board',
                ],
                403
            );
        }

        $user_in_board = UserInBoard::where([
            'user_id' => $valdated['user_id'],
            'board_id' => $valdated['board_id'],
        ])->first();

        if (!$user_in_board) {
            return response(['message' => 'User not found'], 404);
        }

        $user_in_board->update([
            'board_role_id' => $valdated['board_role_id'],
        ]);

        return response(
            [
                'message' => 'Board Role assigned successfully',
            ],
            200
        );
    }

    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $role_board_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.role_board_management')
            ->first()->role_board_management;

        return $role_board_management;
    }
}
