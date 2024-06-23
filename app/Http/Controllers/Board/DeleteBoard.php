<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteBoard extends Controller
{
    public function __invoke(Request $request, string $boardId)
    {
        $board = Board::find($boardId);
        if (!$board) {
            return response(['message' => 'Board not found'], 404);
        }
        if (!$this->checkPermission($board->workspace_id)) {
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
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $delete_board = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.delete_board')
            ->first()->delete_board;

        return $delete_board;
    }
}
