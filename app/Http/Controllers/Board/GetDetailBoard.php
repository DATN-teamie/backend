<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class GetDetailBoard extends Controller
{
    public function __invoke(Request $request, string $board_id)
    {
        $board = Board::find($board_id);
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
