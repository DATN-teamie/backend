<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\UserInBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetDetailBoard extends Controller
{
    public function __invoke(Request $request, string $board_id)
    {
        $board = Board::find($board_id);
        // if user not in board return 403
        $auth_id = Auth::id();
        $user = UserInBoard::where('board_id', $board_id)
            ->where('user_id', $auth_id)
            ->first();
        if (!$user) {
            return response()->json(
                [
                    'message' =>
                        'You do not have permission to access this Board',
                ],
                403
            );
        }
        if (!$board) {
            return response()->json(
                [
                    'message' => 'Board not found',
                ],
                404
            );
        }
        return response()->json([
            'board' => $board,
        ]);
    }
}
