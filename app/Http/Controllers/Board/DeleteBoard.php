<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteBoard extends Controller
{
    public function __invoke(Request $request, string $boardId)
    {
        $board = Board::find($boardId);
        if (!$board) {
            return response(['message' => 'Board not found'], 404);
        }
        if ($board->owner_id != Auth::id()) {
            return response(
                [
                    'message' => 'You are not authorized to delete this board',
                ],
                403
            );
        }
        $board->delete();

        return response(['message' => 'Board deleted successfully'], 200);
    }
}
